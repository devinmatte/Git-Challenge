Git-Challenge
=============

Gamification of Git Contributions for the use of either education or competition.

### Idea
Git Challenge was a project I had an idea for when I looked over a GitHub Organisation I was a part of. It is for my old High School Technology Team, the organisation that taught me most of what I knew about programming before I came here. The projects in the GitHub hadn't been touched by anyone except myself and a few other Team Alumni. So I thought I should come up with a way to encourage contributing to these projects, and to teach people git. So I came up with Git-Challenge. A app made to gamify contributing to projects, for any Organisation. Not just this Tech Team. It could be used for CSH, or really any other git organisation with multiple contributors.

Setup
-----

Create a `configuration.php` based on the `configuration-template.php` and fill if the corresponding values to set up your application.

After the first run you'll have the proper tables you need in the SQL database. Add all the email addresses of the users competing in the challenge. (It's currently opt in. Users need to provide you their GitHub email address to be counted by the system)

The system checks for new commits every load. However it only checks commits it hasn't already checked so slowdown shouldn't be an issue.
