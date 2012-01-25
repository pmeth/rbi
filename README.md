RBI  
===  
  
PROJECT OVERVIEW  
----------------  
  
This is a PHP based editor and abstraction layer for the classic Nintendo Entertainment System (NES) game RBI Baseball 3.  
  
The code works by reading lines from an RBI 3 rom and converting them to human readable language and defining an editable data structure.  
  
Unfortunately I cannot supply you with the actual roms which you will need to make this work.  I suggest heading over to http://dee-nee.com to find roms.  You can create the AndyB rom by applying the patch located in the rompatches folder.  To apply the patch you will need an original RBI 3 rom, then use an IPS Patcher program such as Lunar IPS (LIPS), which can be found at: http://www.romhacking.net/utilities/240/. If you are having a hard time finding a good rom, contact me and I might be able to help.  
  
I created this project partly for my own entertainment and partly as a learning project as I transitioned from Procedural to Object Oriented programming. An unfortunate side effect of this is that you will likely see a strange mixture of Procedural and OO code and you may also see some things that are non-standard and probably quite hideous.  You may also find stubs of partially finished code or half-baked ideas, or even things I thought were cool at the time and that I might use later (does that make me a code hoarder?).  
  
This is a work in progress and in fairly rough condition right now.  You will likely have to be a decent PHP developer to get much out of this project for now. I am working on making it more user friendly and may eventually launch a website where users can upload and edit roms without diving into the code.  
    
You can reach me on twitter at:  
[@mrpmeth](https://twitter.com/mrpmeth)  
  
  
TO GET STARTED  
--------------  
  
1. Download the source and install on a webserver.  You will probably need PHP 5.3+.  
2. There is some incomplete code for registering, logging in and uploading files which requires a MySQL database.  
	* To setup the database, run the queries in the following folder against your database.  
	/sql/users.sql  
	You will want to update the MySQL login credentials to match your database.  You can find that in the file:  
	/bootstrap.php  
	**Note**: *this is incomplete code and using it may introduce security vulnerabilities. That said, I do welcome contributions from anyone who wishes to develop this area further.*  
	* If you want to remove the database requirement, you can comment out the marked lines in bootstrap.php, then don't click on the Login or Register link when running the code.  
3. Put an RBI3 (preferably the version hacked by andyb that gives access to 6 divisions and 30 teams and a few other nice enhancements) in the directory above where you installed this.  
	* so for example if you installed to:  
	/var/www/rbi3editor/rbi  
	you would put your rom at  
	/var/www/rbi3editor/myrom.nes  
	**Note**: *make sure the rom is writeable (chmod 777)*
4. Update the name of the ROM in the bootstrap.php file:  
	$myrom = new RBI3AndyBRom("../rbi2008.nes");  
	Change rbi2008.nes to whatever your rom is called, eg. myrom.nes  
  
	Also, if you are not using the AndyB rom, you should probably use:  
	$myrom = new RBI3Rom("../myrom.nes");  
5. Run the code and hope for the best.  
6. The code will directly edit your rom, so take a backup before getting started.  To play your edited rom, simply run your favourite emulator and load the edited myrom.nes (or whatever it was called).  
  
For further documentation, you can refer to the docs folder.  It may not be very up to date though.  
  
Note, there is a v1 folder that has a more user friendly front end, but the back end is all procedural code.  I will do a separate writeup for how to get started with that one when I get some time.  
  
FUTURE DEVELOPMENT GOALS  
------------------------  
  
- get help from other interested parties  
- centralize the loading of the rom  
- allow for choosing different roms from the front-end  
- allow for uploading roms from the front-end  
- more secure registering and logging in  
- allow storing rom data in a MySQL database  
- expand the player list page to show more attributes and have more filters and sorting (like v1 does)  
- allow for editing loading screens of the rom  
- editing of sprites in the rom  
- changing character sets in the rom  
- edit alternate versions of RBI (RBI 1, RBI 2, RBI 93, etc)  
- expand engine to allow for editing other types of roms (Tecmo bowl would probably be next)  
- integrate with jsnes so you can test on the fly  
  
Thanks,  
Peter