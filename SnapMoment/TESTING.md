# SnapMoment – Testing Checklist

This file contains the tests performed on the SnapMoment website. I checked the main features to make sure they were working properly.

| Test | Expected Result | Result / Notes |
|---|---|---|
| New visitor registration | A unique Visitor ID is generated | Passed |
| Upload valid JPG/PNG/WEBP photo | Photo is uploaded successfully with pending status | Passed |
| Upload unsupported file type | Website shows an error message | Passed |
| Upload photo over size or count limit | Website shows a suitable error message | Passed |
| Returning visitor on the same browser | Existing visitor is recognised | Passed |
| Returning visitor on another browser | Name and Visitor ID retrieve the visitor profile | Passed |
| Admin login | Admin can log in and access the dashboard | Passed |
| Approve a photo | Approved photo appears in the public gallery | Passed |
| Reject a photo | Rejected photo does not appear in the public gallery | Passed |
| Delete a rejected photo | Photo is removed from the database and uploads folder | Passed |
| Public slideshow | Approved photos change automatically | Passed |
| 3D image carousel | Images appear in one row and the carousel works properly | Passed |

## Testing Summary

- Tested the main features of the website using XAMPP.
- Checked visitor registration and photo uploads.
- Checked admin login and photo approval/rejection.
- Checked the public gallery and slideshow.
- Fixed the issue where duplicate carousel images appeared in a second row.
- Tested the updated carousel and confirmed that the issue was fixed.

**Overall Result:** The main website features were tested and are working as expected.
