<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{
    public function showAllPaymentsHistory()
    {
        try {
            $data = Payment::paginate(10);

            if (!$data) {
                throw new \Exception('error: payments not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function showOnePaymentHistory($id)
    {
        try {
            $data = Payment::find($id);

            if (!$data) {
                throw new \Exception('error: payment not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function storePayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $model = new Payment();
            $validator = Validator::make(
                $request->all(),
                $model->rules(),
            );

            if ($validator->fails()) {
                return $this->writeResponse(self::HTTP_STATUS_ERROR, $validator->messages()->get('*'), false);
            }
            $model->fill($request->all());
            $model->password = Hash::make($request->post('password'));

            if (!$model->save()) {
                throw new \Exception('error: created payment failed', 500);
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_CREATED, 'created success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nominal' => 'required|numeric',
                    'periode_start' => 'required|date_format:Y:m:d|before:periode_end',
                    'periode_end' => 'required|date_format:Y:m:d|after:periode_start',
                    'user_id' => 'required|numeric',
                    'student_id' => 'required|numeric',
                    'payment_type_id' => 'required|numeric',
                ],
            );

            if ($validator->fails()) {
                return $this->writeResponse(self::HTTP_STATUS_ERROR, $validator->messages()->get('*'), false);
            }

            $model = Payment::find($id);
            if (!$model) {
                throw new \Exception('error: payment not found', 500);
            }
            $model->fill($request->all());

            if (!$model->save()) {
                throw new \Exception('error: updated payment failed', 500);
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_SUCCESS, 'updated success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }
}
