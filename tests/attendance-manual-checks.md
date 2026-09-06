# Attendance kiosk checks

Run `D:/XAMPP/php/php.exe tests/attendance-persistence.php` for isolated monthly generation, selective updates, and rollback checks. This test uses an in-memory database and does not modify employee attendance.

On the attendance unit, open `/beetpress/in-out` using localhost or HTTPS:

1. Enter letters or punctuation in Employee ID and PIN: only digits should remain, including leading zeros.
2. Enter incorrect credentials and click an attendance button: no profile, camera capture, or attendance save should occur.
3. Enter valid credentials and click an attendance button: confirm verification displays the existing employee photo, name, and office title, then automatically captures and saves the selected attendance time. The PIN is cleared after verification. Buttons and inputs are disabled while processing; repeated clicks must not submit again.
4. Confirm that only AM buttons appear before noon and only PM buttons appear from noon, using Asia/Manila server time.
5. Deny camera permission or disconnect the camera: attendance must not save, and an explanatory message should appear.
6. Face away from the default camera: the page should ask you to face the camera and must not save.
7. Face the camera and record attendance: confirm the appropriate DTR field and a JPEG at `public/employee_attendance_capture/{EmployeeID}/YYYY/MM/DD/{EmployeeID}-YYYY-MM-DD-HHMMSS.jpg`. Repeat another attendance action and confirm other time fields remain intact.
8. Verification must expire after two minutes or reset when leaving the page. After a successful save, credentials are cleared. After a camera or save failure, enter the PIN again and click the desired attendance button to retry verification and recording.
9. After a successful save, confirm the employee photo (or saved camera capture when no profile photo is available), name, and office remain visible. Confirm the dated table displays all four stored times, including earlier entries, and a dash for missing entries. Starting to enter the next employee's credentials hides the previous result.
10. Allow location access and save each attendance action during its allowed period. Confirm latitude,longitude is stored in the matching attendance_gps_location_am_in, attendance_gps_location_am_out, attendance_gps_location_pm_in, or attendance_gps_location_pm_out column, preserving the other entries' locations.
11. Deny location access, disable location services, or let the location request time out: an explanatory message must appear and attendance must not save. Retry with permission enabled and confirm a fresh location is requested. Missing, nonnumeric, or out-of-range coordinates submitted to the kiosk API must be rejected without saving.

Detection uses the local face-api.js Tiny Face Detector on the exact uploaded frame. It checks face presence in the browser; it is not identity matching, liveness verification, or server-side proof of a face. Browsers still display camera permission and usage indicators. Existing legacy GET API integrations retain their existing authentication behavior.
