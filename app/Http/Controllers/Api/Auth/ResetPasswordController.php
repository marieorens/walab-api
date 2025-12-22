<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtpVerifyRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\auth\ResetPasswordRequest;
// use Carbon\Carbon;
use Exception;
use Ichtrojan\Otp\Models\Otp as Model;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * reset password
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->update(
            [
                'password' => Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();
        $success['succees'] = true;
        return response()->json($success, 200);
    }

    /**
     * verification Otp
     */
    public function otpVerify(OtpVerifyRequest $request)
    {
        $otp2 = $this->validate($request->email, $request->otp);
        if (!$otp2->status) {
            return response()->json(['error' => $otp2], 401);
        }
        $success['succees'] = true;
        return response()->json($success, 200);
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */
    public function validate(string $identifier, string $token): object
    {
        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp instanceof Model) {
            if ($otp->valid) {
                $now = now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                $otp->update(['valid' => false]);

                if (strtotime($validity) < strtotime($now)) {
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                $otp->update(['valid' => false]);

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];
            }

            $otp->update(['valid' => false]);

            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
    }
}
