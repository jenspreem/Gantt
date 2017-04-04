#SINGLE CHART
* modTask
    * recreate modform on errors
    * code to reduce dayrange if needed
* remTask
    * add code to only fetch new dayrange from server if dayrange actually changes
    * code to reduce dayrange if needed


#MULTI USER
##ACCOUNT CREATION AND MANAGEMENT
* possibility to change password

#LOOKS
##CHARTS
* modify task and delete task as **buttons** --done!
* beautified modal alerts instead of confirms alerts etc
* beautified header1 months,days
* ?beautified header2, interactive to allow week/month etc views?
* ?frozen columns - priority
* task colors
* drag-n-drop on tasks?

#CODE REFACTORING

* cut down on globals
    * remove
    * move to functions
* cut down on repeating code


#SECURITY
* create sessions ID? right now after confirmation user ID is retrieved
and used for all requests 
    * this could be a risk if someone manages to post numbers to php scripts
    * so maybe - on login script create session password and hash no retrieves without it

#ADDITIONAL FEATURES
* maybe allow people to make personal calendars also in addition to Gantt charts?
* collapsible/expandable tasks
