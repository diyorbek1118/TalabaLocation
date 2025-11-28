<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\User;
use SimpleXMLElement;

class XmlDownloadController extends Controller
{
    public function XmlDownload($model)
    {
        if ($model === 'rents') {

            $data = Rent::all();

            $xml = new SimpleXMLElement('<rents/>');

            foreach ($data as $rent) {
                $rentNode = $xml->addChild('rent');
                $rentNode->addChild('id', $rent->id);
                $rentNode->addChild('student_id', $rent->student_id);
                $rentNode->addChild('price', $rent->price);
                $rentNode->addChild('date', $rent->created_at);
            }

            $fileName = 'rents.xml';
        }

        else if ($model === 'students') {

            $students = User::with('studentProfile')
                ->where('role', 'student')
                ->get();

            $xml = new SimpleXMLElement('<students/>');

            foreach ($students as $student) {
                $studentNode = $xml->addChild('student');
                $studentNode->addChild('id', $student->id);
                $studentNode->addChild('name', $student->name);
                $studentNode->addChild('phone', $student->phone);

                if ($student->studentProfile) {
                    $studentNode->addChild('faculty', $student->studentProfile->faculty);
                    $studentNode->addChild('address', $student->studentProfile->rent_address);
                    $studentNode->addChild('group', $student->studentProfile->group_name);
                    $studentNode->addChild('tutor', $student->studentProfile->tutor);
                    $studentNode->addChild('course', $student->studentProfile->course);
                    $studentNode->addChild('gender', $student->studentProfile->gender);
                }
            }

            $fileName = 'students.xml';
        }

        else {
            return response()->json([
                'error' => 'Model not found'
            ], 404);
        }

        return response()->streamDownload(function () use ($xml) {
            echo $xml->asXML();
        }, $fileName, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
