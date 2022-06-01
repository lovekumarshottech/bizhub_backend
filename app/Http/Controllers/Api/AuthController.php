<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ferdous\OtpValidator\OtpValidator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Ferdous\OtpValidator\Object\OtpRequestObject;
use Illuminate\Support\Facades\{DB, Auth, Mail, Hash};
use App\Mail\{WelcomeMail, ForgotPasswordMail};
use Ferdous\OtpValidator\Object\OtpValidateRequestObject;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\{ValidateOtpRequest, RegisterUserRequest, ResendOtpRequest, LoginUserRequest, UpdatePasswordRequest, UpdateProfileRequest, ForgetPasswordRequest, ResetPasswordRequest};

class AuthController extends BaseController
{
    public function register(RegisterUserRequest $request)
    {
        $input = $request->validated();
        $input["device_id"] = $request->device_id ? $request->device_id : "";
        $input["device_type"] = $request->device_type ? $request->device_type : "Android";
        if ($request->image) {
            list($type, $request->image) = explode(',', $request->image);
            $imageName = sprintf("%s.%s", time(), 'png');
            $path = 'uploads/users/profiles/' . $imageName;
            Storage::disk('local')->put($path, base64_decode($request->image));
            $input["image"] = $path;
            // echo storage_path('public/'.$path);
            // $imagePath = Storage::disk('local')->path($path);
            // echo $imagePath;
        }
        try {
            $user = User::create($input);
            Mail::to($input['email'])->send(new WelcomeMail($input['first_name'], $input['last_name'], $input['email'], $input['password']));
            $otp = OtpValidator::requestOtp(
                new OtpRequestObject(1234, $user->phone, 'otp',  $user->email)
            );
            return $this->sendResponse(Response::HTTP_CREATED, $otp, 'Account created successfully. Check Email to verify your account.');
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function validateOtp(ValidateOtpRequest $request)
    {
        $response = OtpValidator::validateOtp(new OtpValidateRequestObject($request->uniqueId, $request->otp));
        if ($response["code"] == 200) {
            $data = DB::table('otps')
                ->where('uuid', '=', $request->uniqueId)
                ->where('otp', '=', $request->otp)
                ->first();
            DB::table('otps')->where('uuid', $request->uniqueId)->delete();
            $user = User::where('email', $data->email)->where('phone', $data->number)->first();
            $user->is_active = "1";
            $user->is_verified = "1";
            $user->save();
            $success = $this->generateToken($user);
            return $this->sendResponse(Response::HTTP_OK, $success, 'Your account verified successfuly.');
        } else {
            return $this->sendError($response["error"], $response, $response["code"]);
        }
    }



    public function resendOtp(ResendOtpRequest $request)
    {
        $resend = OtpValidator::resendOtp($request->uniqueId);
        if ($resend == 0) {

            return $this->sendError("Otp Resend Failed.", [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $resend;
    }


    public function authenticate(LoginUserRequest $request)
    {

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError('Login credentials are invalid.', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user = User::whereEmail($request->email)->first();
            if ($user->is_verified == "0") {
                return $this->sendError('Your account is not verfied. Please check your e-mail to verify your account.', [], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else if ($user->is_active == "0") {
                return $this->sendError('Your account is in-active. Please contact Admin', [], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user = Auth::user();
                $user->device_id = $request->device_id ? $request->device_id : "";
                $user->device_type = $request->device_type ? $request->device_type : "Android";
                $user->save();
                $success = $this->generateToken($user);
                return $this->sendResponse(Response::HTTP_OK, $success, 'User Login Successfully');
            }
        }
    }

    public function logout()
    {
        $user = Auth::user()->token();

        $userObject = Auth::user();
        $userObject->device_id = '';
        $userObject->save();
        $user->revoke();
        return $this->sendResponse(Response::HTTP_OK, [], 'User logged out successfully.');
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Invalid current password.', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user->password = $request->new_password;
            $user->save();
            return $this->sendResponse(Response::HTTP_OK, [], 'Password updated Successfully');
        }
    }

    public function profile(Request $request)
    {
        return $this->sendResponse(Response::HTTP_OK, $request->user(), 'Profile get successfully');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $input = $request->validated();
        $user = $request->user();
        if ($request->has('image')) {
            Storage::delete($user->image);
            list($type, $request->image) = explode(',', $request->image);
            $imageName = sprintf("%s.%s", time(), 'png');
            $path = 'uploads/users/profiles/' . $imageName;
            Storage::disk('local')->put($path, base64_decode($request->image));
            $input["image"] = $path;
        }
        $user->update($input);
        return $this->sendResponse(Response::HTTP_OK, $user, 'Profile updated successfully');
    }

    public function generateToken($user): array
    {
        return [
            'token' => $user->createToken($user->email)->accessToken,
            'user' => $user
        ];
    }


    public function forgetPassword(ForgetPasswordRequest $request)
    {
        if (User::where('email', $request->email)->exists()) {
            $token = $this->forgotPasswordToken($request->email);
            return $this->sendResponse(Response::HTTP_OK, [], 'A confirmation email has been sent to change your password.');
        } else {
            return $this->sendError('No such email found !', [], Response::HTTP_NOT_FOUND);
        }
    }

    protected function forgotPasswordToken($email)
    {
        $token = Str::random(4);
        User::where('email', $email)->update(["forgot_token" => $token]);
        Mail::to($email)->send(new ForgotPasswordMail($token));
        return $token;
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $whereArray = [
            'email' => $request->email,
            'forgot_token' => $request->token
        ];
        if (User::where($whereArray)->exists()) {
            $user = User::where($whereArray)->first();
            $user['forgot_token'] =  '';
            $user['password'] = $request->password;
            $user->save();
            return $this->sendResponse(Response::HTTP_OK, [], 'Password reset successfully.');
        } else {
            return $this->sendError('Invalid email or reset token!', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
