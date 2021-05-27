<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllStudents()
    {
        try {
            $data = Student::with('classRelationship')->get();

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
}
