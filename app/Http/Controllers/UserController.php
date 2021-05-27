<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function showAllUsers(Request $request)
    {
        try {
            $data = User::with('levelRelationship')->paginate(10);

            if (!$data) {
                throw new \Exception('error: users not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function showOneUser(Request $request, $id)
    {
        try {
            $data = User::with('levelRelationship')->find($id);

            if (!$data) {
                throw new \Exception('error: user not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function storeUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $model = new User();
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
                throw new \Exception('error: created user failed', 500);
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_CREATED, 'created success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function updateUser(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
                    'level_id' => 'required|numeric',
                    'is_active' => 'required|numeric',
                ],
            );

            if ($validator->fails()) {
                return $this->writeResponse(self::HTTP_STATUS_ERROR, $validator->messages()->get('*'), false);
            }

            $model = User::find($id);
            if (!$model) {
                throw new \Exception('error: user not found', 500);
            }
            $model->fill($request->all());

            if (!$model->save()) {
                throw new \Exception('error: updated user failed', 500);
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_SUCCESS, 'updated success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }
}
