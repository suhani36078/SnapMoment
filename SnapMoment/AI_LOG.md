# SnapMoment – AI Interaction Log

This log records key AI-assisted decisions and development interactions for the project. It includes the prompts/tasks, the AI's contribution, and examples of issues that were identified and corrected. The descriptions are based on the development conversation; they are not a substitute for actual source-control history.

## Key AI Interactions

### 1. Initial project planning
**Prompt/task:** Help plan a visitor photo slideshow website where visitors enter their name, upload photos, and approved photos are displayed on a screen.

**AI contribution:** Helped break the project into visitor, upload, admin moderation, gallery, and slideshow modules. Suggested documenting assumptions such as visitor identification, consent, file validation, upload limits, and simultaneous uploads.

**Outcome:** The project scope was organised into manageable modules.

### 2. Choosing the visitor identification method
**Prompt/task:** Simplify visitor registration and avoid requiring an email address.

**AI contribution:** Helped use name-only registration with a generated Visitor ID, rather than requiring email.

**Outcome:** The visitor flow asks for a name and provides a Visitor ID.

### 3. Returning visitor support
**Prompt/task:** Make it possible for a returning visitor to access their previous photos.

**AI contribution:** Helped add browser-cookie recognition and an optional Visitor ID field for recognition from another browser/device. A photo-history endpoint was added to retrieve the visitor's previous uploads and statuses.

**Outcome:** Returning visitor support uses the visitor cookie on the same browser, or name plus Visitor ID on another browser/device.

### 4. Admin photo moderation
**Prompt/task:** Ensure visitor photos do not appear publicly until an admin reviews them.

**AI contribution:** Helped implement pending, approved, and rejected statuses in the database and admin interface.

**Outcome:** The public gallery API selects approved photos only.

### 5. Improve the homepage gallery
**Prompt/task:** Add a modern 3D hover carousel to the existing three-photo homepage section without redesigning the website or changing the PHP/MySQL functionality.

**AI contribution:** Suggested a horizontal image carousel with hover zoom, lift, tilt, perspective movement, automatic movement while hovered, and touch support.

**Outcome:** The existing approved gallery photos are used as carousel content.

### 6. Add permanent deletion for rejected photos
**Prompt/task:** Add a way for the administrator to permanently delete rejected photos.

**AI contribution:** Helped add a confirmation step, a session-bound CSRF token, a check that the photo is still rejected, and deletion of the associated image file and database record.

**Outcome:** The admin panel offers permanent deletion only for rejected photos.

### 7. Prepare repository documentation
**Prompt/task:** Prepare `README.md`, `ASSUMPTIONS.md`, and `AI_LOG.md` for the project repository.

**AI contribution:** Drafted setup instructions, project features, technical assumptions, and a record of important AI-assisted interactions and corrections.

**Outcome:** These three Markdown files are prepared for review and inclusion in the GitHub repository.

## AI Mistakes, Detection, and Corrections

### Mistake 1: Carousel duplicates appeared in a second row
**What happened:** The first carousel implementation displayed repeated images below the original gallery row. The cloned carousel images were being affected by the existing gallery grid layout instead of staying in one horizontal track.

**How it was noticed:** The developer opened the homepage in the browser and shared a screenshot showing the original images plus repeated images in a second row.

**Correction:** The carousel layout was adjusted so the images are handled in a single horizontal track with the carousel viewport controlling overflow. The developer tested the corrected version and confirmed that the duplicate-row issue was fixed.

**Lesson learned:** An animation or carousel must be checked against the existing page layout; adding JavaScript clones alone is not enough if the original CSS grid rules still apply.

### Mistake 2: Returning visitor support was initially incomplete
**What happened:** Cookie-based recognition worked for the same browser, but that alone did not let a visitor retrieve their profile from a different browser/device.

**How it was noticed:** The project requirement that returning visitors should be able to access their previous photos was reviewed, including the cross-device use case.

**Correction:** An optional Visitor ID field was added. The identify endpoint can look up the visitor using the Visitor ID and matching name when there is no recognised visitor cookie. A photo-history endpoint displays the visitor's previous uploads and their moderation statuses.

**Lesson learned:** A feature should be checked against all intended user scenarios, not only the easiest scenario (in this case, returning on the same browser).

## Verification Notes

- The developer reported that website testing was completed.
- The carousel duplicate-row issue was specifically observed in a browser screenshot and then reported fixed by the developer.
- The documents do not claim that every possible browser, hosting environment, file size, or simultaneous-upload scenario has been independently tested by the AI.
- For the final submission, add the actual test date, browser, test data, expected result, and observed result to a separate testing report if required by the assignment.
