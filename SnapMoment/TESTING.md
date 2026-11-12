# SnapMoment – Testing Checklist

Use this checklist to record actual results before submission. Do not mark a test as passed until it has been run.

| Test | Expected result | Result / notes |
|---|---|---|
| New visitor registration | A unique Visitor ID is generated | Not recorded |
| Upload valid JPG/PNG/WEBP | Upload is saved with pending status | Not recorded |
| Upload unsupported file type | Upload is rejected | Not recorded |
| Upload over size/count limit | Upload is rejected with a useful message | Not recorded |
| Returning visitor, same browser | Existing visitor is recognised | Not recorded |
| Returning visitor, another browser | Name + Visitor ID retrieves profile | Not recorded |
| Admin login | Valid admin can access moderation | Not recorded |
| Approve photo | Photo appears in public gallery/display | Not recorded |
| Reject photo | Photo does not appear publicly | Not recorded |
| Delete rejected photo | Database record and image file are removed | Not recorded |
| Slideshow | Approved photos rotate automatically | Not recorded |
