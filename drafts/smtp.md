
1) get a mx record via nslookup for target domain, I usually do a random sort, then take the last entry for randomness
$ nslookup -type=mx bayer.com | grep "mail exchanger" | sort -R | tail -n1
bayer.com       mail exchanger = 300 smtpmx06.bayer.de.

2) telnet to port 25 on that host
$ telnet smtpmx06.bayer.de 25
Trying 212.64.225.216...
Connected to smtpmx06.bayer.de.
Escape character is '^]'.
220 smtpmx06.bayer.de SMTP Service ready

3) tell it 'helo' and give a domain 
HELO gmail.com
250 smtpmx06.bayer.de Hello gmail.com (24.107.158.189)

4) define a from address
mail from: <bob@google.com>
250 sender ok <bob@google.com>

5) try a recipeitn address to see if that account exists on the mailserver
rcpt to: <lksdajflkdsjafl.WRONGEMAIL@bayer.com>
550 Mail could not be processed

6) try another
rcpt to: <.foobar@bayer.com>
isajdflkdsj
550 Mail could not be processed

7) try another
rcpt to: <alkadlfkajsdlf.ext@bayer.com>
550 Please contact the administrator of your mail system. Your mail was rejected because your Internet mail server xx.xx.xx.xx is contained in the DNS blacklist: spamhaus.org.

8) leave
QUIT
Connection closed by foreign host.
