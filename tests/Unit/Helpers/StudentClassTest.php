<?php

use App\Helpers\StudentClass;

// Note: These tests are placeholders since the StudentClass helper depends on
// models (StudentAttendance, StudentGradeHistory, StudentTransferredCourse) that
// may not be available in the test environment. Update these tests when the
// required models and database tables are available.

test('StudentClass has getEnrolledClasses method', function () {
    expect(method_exists(StudentClass::class, 'getEnrolledClasses'))->toBeTrue();
});

test('StudentClass has getCompletedClasses method', function () {
    expect(method_exists(StudentClass::class, 'getCompletedClasses'))->toBeTrue();
});

test('StudentClass has getTransferredCourses method', function () {
    expect(method_exists(StudentClass::class, 'getTransferredCourses'))->toBeTrue();
});

test('getEnrolledClasses accepts correct parameters', function () {
    $reflection = new ReflectionMethod(StudentClass::class, 'getEnrolledClasses');
    $parameters = $reflection->getParameters();

    expect($parameters)->toHaveCount(2)
        ->and($parameters[0]->getName())->toBe('termCode')
        ->and($parameters[1]->getName())->toBe('uin');
});

test('getCompletedClasses accepts correct parameters', function () {
    $reflection = new ReflectionMethod(StudentClass::class, 'getCompletedClasses');
    $parameters = $reflection->getParameters();

    expect($parameters)->toHaveCount(2)
        ->and($parameters[0]->getName())->toBe('termCode')
        ->and($parameters[1]->getName())->toBe('uin');
});

test('getTransferredCourses accepts correct parameters', function () {
    $reflection = new ReflectionMethod(StudentClass::class, 'getTransferredCourses');
    $parameters = $reflection->getParameters();

    expect($parameters)->toHaveCount(2)
        ->and($parameters[0]->getName())->toBe('uin')
        ->and($parameters[1]->getName())->toBe('termCode');
});

test('all StudentClass methods are static', function () {
    $methods = ['getEnrolledClasses', 'getCompletedClasses', 'getTransferredCourses'];

    foreach ($methods as $method) {
        $reflection = new ReflectionMethod(StudentClass::class, $method);
        expect($reflection->isStatic())->toBeTrue("Method {$method} should be static");
    }
});

test('StudentClass methods have Collection return type hint', function () {
    $methods = ['getEnrolledClasses', 'getCompletedClasses'];

    foreach ($methods as $method) {
        $reflection = new ReflectionMethod(StudentClass::class, $method);
        $returnType = $reflection->getReturnType();

        // Verify return type is declared
        expect($returnType)->not->toBeNull("Method {$method} should have a return type");

        // Collection is the expected return type
        $typeName = $returnType->getName();

        // Check for Collection type (either short or fully qualified)
        expect(
            $typeName === 'Collection' ||
            $typeName === 'Illuminate\Support\Collection' ||
            str_ends_with($typeName, '\Collection')
        )->toBeTrue("Method {$method} should return Collection type, got {$typeName}");
    }
});

test('getTransferredCourses method exists and is callable', function () {
    // getTransferredCourses may have a different signature, so test it separately
    expect(method_exists(StudentClass::class, 'getTransferredCourses'))->toBeTrue()
        ->and(is_callable([StudentClass::class, 'getTransferredCourses']))->toBeTrue();
});

// When the required models are available, you can add integration tests like:
//
// test('getEnrolledClasses returns enrolled courses for student', function () {
//     // Create test data
//     $termCode = '202401';
//     $uin = '123456789';
//
//     // Create StudentAttendance and related data
//     // ...
//
//     $courses = StudentClass::getEnrolledClasses($termCode, $uin);
//
//     expect($courses)->toBeInstanceOf(Collection::class);
// });
