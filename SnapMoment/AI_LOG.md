# SnapMoment – AI Interaction Log

This file explains how I used AI while making my SnapMoment project. I have mentioned the main changes I made with AI's help, along with some mistakes that happened during development and how I fixed them.

## Key AI Interactions

### 1. Planning the Website
**What I asked:** Help me plan a website where visitors can enter their name, upload photos, and see approved photos on a slideshow.

**How AI helped:** It suggested different sections like visitor registration, photo upload, admin panel, gallery, and slideshow.

**What I did:** I used these ideas to plan my website step by step.

### 2. Visitor Registration
**What I asked:** I wanted visitors to register without entering their email address.

**How AI helped:** It suggested using the visitor's name and giving them a unique Visitor ID.

**What I did:** I added the Visitor ID feature so visitors can use it again to access their photos.

### 3. Returning Visitors
**What I asked:** I wanted returning visitors to see their previously uploaded photos.

**How AI helped:** It suggested using cookies for the same browser and a Visitor ID for another browser or device.

**What I did:** I added this feature so visitors can check their previous photos and their status.

### 4. Admin Photo Approval
**What I asked:** I did not want uploaded photos to appear directly on the public website.

**How AI helped:** It suggested three photo statuses: Pending, Approved, and Rejected.

**What I did:** I added the approval system. Now only approved photos appear in the public gallery and slideshow.

### 5. Making the Gallery Better
**What I asked:** I wanted to make the three-photo section on my homepage more attractive and interactive.

**How AI helped:** It suggested a 3D carousel with hover effects, zoom, movement, and touch support.

**What I did:** I added the carousel using the existing approved photos without changing the main website design.

### 6. Deleting Rejected Photos
**What I asked:** I wanted the admin to permanently delete rejected photos.

**How AI helped:** It suggested adding a delete option with a confirmation message and security checks.

**What I did:** I added the delete option in the admin panel. It deletes both the photo record and its image file.

### 7. Project Documentation
**What I asked:** I needed project documentation for my GitHub repository.

**How AI helped:** It helped me prepare README.md, ASSUMPTIONS.md, AI_LOG.md, ARCHITECTURE.md, and TESTING.md.

**What I did:** I added these files to my repository to explain the project, its features, and how it works.

## Mistakes Made During Development

### Mistake 1: Duplicate Photos in the Carousel

**What happened:** When I first added the 3D carousel, some photos appeared again in a second row.

**How I noticed it:** I opened the website and saw that the photos were repeating below the main row. I also shared a screenshot with AI.

**How I fixed it:** AI helped me change the carousel layout so that all photos stayed in one horizontal row. I checked it again in the browser, and the issue was fixed.

### Mistake 2: Returning Visitor Feature Was Incomplete

**What happened:** Initially, returning visitors could be recognised only through the same browser. They could not easily access their photos from another device.

**How I noticed it:** I realised that the feature would not work properly if someone opened the website on a different browser or device.

**How I fixed it:** I added the Visitor ID option along with the visitor's name. This allows visitors to access their previous photo history from another device.

## My Experience with AI

AI helped me understand problems and make changes in my project, but not every suggestion worked correctly the first time. I had to check the website in the browser, share screenshots, and ask for corrections. This helped me understand that AI is useful during development, but we should always test the changes ourselves.
