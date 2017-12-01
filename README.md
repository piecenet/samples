These sample scripts were originally developed when PHP had rather limited OOP (Object Oriented Programming) support. They worked well with minor modifications and so were never converted to use OOP coding. They all include self-documentation as it sometimes would be over a year before I had to modify them and the documentation would help refresh my memory.

formvalfunctions.php contains several functions that I used to create MySQL statments from within other scripts. It made it easier to adapt pages to very different uses or customize scripts for specific clients.

functionImageUpload.php and functionPDFUpload.php were used by various scripts to allow privileged users to be able to upload images or PDF files from within admin pages.

getaddressparts.php was used to parse an address. Most sources of addresses (and most web forms) just use one address line as that is what most people are familiar with. The script would parse this into the various parts (street number, pre-directional, name, post-directional, street type, unit, etc.). This helped in database searches and then for output, the parts would be combined to one line.

userdatacleanup.php was used to make user data consistent within a database.

I have wanted to convert the above files to an OOP format, but time and economics has not allowed me the freedom to do that just yet.
