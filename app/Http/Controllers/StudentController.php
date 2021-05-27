<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function showAllStudents()
    {
        try {
            $data = Student::with('classRelationship')->paginate(10);

            if (!$data) {
                throw new \Exception('error: students not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function showOneStudent($id)
    {
        try {
            $data = Student::with('classRelationship')->find($id);

            if (!$data) {
                throw new \Exception('error: student not found', 500);
            }

            return $this->writeResponseBody(self::HTTP_STATUS_SUCCESS, '', $data, true);
        } catch (\Exception $e) {
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function storeStudents(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'students.*.nisn' => 'required|numeric|unique:students',
                    'students.*.name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
                    'students.*.address' => 'required|max:200',
                    'students.*.class_id' => 'required|numeric',
                ],
            );

            if ($validator->fails()) {
                return $this->writeResponse(self::HTTP_STATUS_ERROR, $validator->messages()->get('*'), false);
            }

            foreach ($request->all()['students'] as $student) {
                $model = new Student();
                $model->fill($student);

                if (!$model->save()) {
                    throw new \Exception('error: created students failed', 500);
                }
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_CREATED, 'created success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }

    public function updateStudent(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nisn' => 'required|numeric|unique:students,nisn,'.$id,
                    'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
                    'address' => 'required|max:200',
                    'class_id' => 'required|numeric',
                ],
            );

            if ($validator->fails()) {
                return $this->writeResponse(self::HTTP_STATUS_ERROR, $validator->messages()->get('*'), false);
            }

            $model = Student::find($id);
            if (!$model) {
                throw new \Exception('error: student not found', 500);
            }
            $model->fill($request->all());

            if (!$model->save()) {
                throw new \Exception('error: updated students failed', 500);
            }

            DB::commit();
            return $this->writeResponse(self::HTTP_STATUS_SUCCESS, 'updated success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->writeResponse(self::HTTP_STATUS_ERROR, $e->getMessage(), false);
        }
    }
}
