#SINGLE CHART
* modTask
    * code to shrink dayrange only  if needed??? might actually not be so optimal
* remTask
    * code to shrink dayrange only if needed??? might actually not be so optimal

#MULTI USER
##ACCOUNT CREATION AND MANAGEMENT
* possibility to change password

#LOOKS
##CHARTS
* beautified modal alerts instead of confirms alerts etc
* beautified header1 months,days
* ?beautified header2, interactive to allow week/month etc views?
* frozen columns beautify styling -priority
* task colors
* drag-n-drop on tasks?

#CODE REFACTORING

* cut down on globals
    * remove
    * move to functions
* cut down on repeating code
* should I do something with synchronous getDayrange function???


#SECURITY
* create sessions ID? right now after confirmation user ID is retrieved
and used for all requests 
    * this could be a risk if someone manages to post numbers to php scripts
    * so maybe - on login script create session password and hash no retrieves without it

#ADDITIONAL FEATURES
* maybe allow people to make personal calendars also in addition to Gantt charts?
* collapsible/expandable tasks
