Git-Challenge
=============
[![Build Status](https://travis-ci.org/devinmatte/Git-Challenge.svg?branch=master)](https://travis-ci.org/devinmatte/Git-Challenge)


Gamification of Git Contributions for the use of either education or competition.

### Idea
Git Challenge was a project I had an idea for when I looked over a GitHub Organisation I was a part of. It is for my old High School Technology Team, the organisation that taught me most of what I knew about programming before I came here. The projects in the GitHub hadn't been touched by anyone except myself and a few other Team Alumni. So I thought I should come up with a way to encourage contributing to these projects, and to teach people git. So I came up with Git-Challenge. A app made to gamify contributing to projects, for any Organisation. Not just this Tech Team. It could be used for CSH, or really any other git organisation with multiple contributors.

Setup
-----

Create a `configuration.php` based on the `configuration-template.php` and fill if the corresponding values to set up your application.

After the first run you'll have the proper tables you need in the SQL database. The first Run(s) will be the slowest as it has to track and add every new user it comes across and every commit. GitHub has a 5000 API calls an Hour Limit. By default there is a limit on how many API calls will be made each load. That limit can be modified in the Configuration file. It is reccommended that you start with a low amount (under 2500) so that you don't overwhelm the program. Also it is reccommended to do the initial data pulls before deployment to a public url so that you don't hit the API limit. Small orgs (1000 or less Commits) need not worry about this.

The system checks for new commits every load. However it only checks commits it hasn't already checked so slowdown shouldn't be an issue after the initial data pull.

Examples
--------

- [ComputerScienceHouse](https://csh.rit.edu/~matted/Git-Challenge/)
- [NHSTechTeam](http://nhstech.us/Git-Challenge/)
