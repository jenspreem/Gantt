#SINGLE CHART
* modTask
    * recreate modform on errors
    * code to reduce dayrange if needed
* remTask
    * add code to only fetch new dayrange from server if dayrange actually changes
    * code to reduce dayrange if needed
* move delete chart button to apparea
* create a settings file for all php scripts
#MULTI USER
##ACCOUNT CREATION AND MANAGEMENT
* possibility to change password

#LOOKS
##CHARTS
* beautified modal alerts instead of confirms alerts etc
* beautified header1 months,days
* ?beautified header2, interactive to allow week/month etc views?
* ?frozen columns

#CODE REFACTORING

* cut down on globals
    * remove
    * move to functions
* cut down on repeating code


#SECURITY
* store sensitive info somewhere outside browser accessible folder  -- done
* create sessions ID? right now after confirmation user ID is retrieved
and used for all requests 
    * this could be a risk if someone manages to post numbers to php scripts
    * so maybe - on login script create session password and hash no retrieves without it

