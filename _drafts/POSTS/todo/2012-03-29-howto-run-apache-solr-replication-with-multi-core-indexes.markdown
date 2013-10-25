---
author: fak3r
comments: true
date: 2012-03-29 22:13:08
layout: post
slug: howto-run-apache-solr-replication-with-multi-core-indexes
title: HOWTO run Apache Solr replication with multi-core indexes
wordpress_id: 3691
categories:
- geek
- howto
- linux
tags:
- apache solr
- multi-core
- replication
- solr
---

[caption id="attachment_3708" align="alignright" width="300" caption="Apache Solr"][![Apache Solr](http://fak3r.com/wp-content/blogs.dir/12/files/logo_apache_solr-300x151.png)](http://fak3r.com/2012/03/29/howto-run-apache-solr-replication-with-multi-core-indexes/logo_apache_solr/)[/caption]

After having an Apache Solr search server running across multicores (that's multiple "search" cores, it has nothing to do with multicore processors) we need to expand it by using replication for backups, as well as eventual load balancing for performance reasons. The 8 cores' indexes are currently about 18Gig, so it's no small undertaking to backup and move these suckers around, so we're using KVM vitual machines as new slave nodes. I setup these up on a stock Debian Squeeze (6.0) install and read up on how to make a master/slave Solr replication work. Since version 1.4 Solr boasts built in replication, so any external rsync enabled scripts are no longer needed. For this you'll need at least two Solr servers running, though my directions are in Debian, they'll be indentical if you're running Ubuntu, and the same, after the Tomcat home directory, on other Linux distros. <!-- more -->


### Master Apache Solr node


Turning on replication is pretty simple, first go to the base of your Solr install on your master node, and add a new stanza to your solrconfig.xml. By default this is /var/lib/tomcat6/solr/conf/solrconfig.xml I added mine at the bottom of the file just so I could find it and keep it seperate from the verbose, comment heavy config file.

    
    <requestHandler name="/replication" class="solr.ReplicationHandler" >
     <lst name="master">
     <str name="replicateAfter">startup</str>
     <str name="replicateAfter">commit</str>
     <!-- <str name="backupAfter">optimize</str> -->
     <str name="confFiles">admin-extra.html,elevate.xml,mapping-FoldToASCII.txt,mapping-ISOLatin1Accent.txt,protwords.txt,schema.xml,scripts.conf,spellings.txt,stopwords_en.txt,stopwords.txt,synonyms.txt</str>
     <str name="commitReserveDuration">00:00:10</str>
     </lst>
     <str name="maxNumberOfBackups">1</str>
     </requestHandler>


Some notes, most of these variables are self-explainable, so read them over and consider the defaults for now. One thing I used right away was confFiles, this tells Solr that you want to keep the master copies of the text files listed in sync with the slave nodes. In my testing I had solrconfig.xml in that list, but that failed because once it syncned the master solrconfig.xml to the slaves, the slaves becaome masters too, and stopped syncing - subprime to say the least. The last thing you need to do on the master is turn on replication for each core, again, this is pretty straightforward, again we need to edit the solrconfig.xml, but this time for the core we want to replicate. For this example my core (activity_logs) will be under the default Solr path, so I'll edit /var/lib/tomcat6/solr/cores/activity_logs/conf/solrconfig.xml and again add this to the bottom of my file.

    
    <requestHandler name="/replication" class="solr.ReplicationHandler" >
     <lst name="master">
     <str name="replicateAfter">commit</str>
     <str name="confFiles">admin-extra.html,elevate.xml,mapping-FoldToASCII.txt,mapping-ISOLatin1Accent.txt,protwords.txt,schema.xml,scripts.conf,spellings.txt,stopwords_en.txt,stopwords.txt,synonyms.txt</str></str>
     </lst>
     </requestHandler>


Again, pretty simple, this is just a paired down version of the first one, again with the option confFiles block to keep the text and confiles in sync across the replicated cores.


### Slave Apache Solr node(s)


On to the Slave node, we need to edit the same solrconfig.xml files we did on the Master, with slight differences. First the main solrconfig, which my example is /var/lib/tomcat6/solr/conf/solrconfig.xml

    
    <requestHandler name="/replication" class="solr.ReplicationHandler" >
     <lst name="slave">
     <str name="masterUrl">http://128.128.164.109:8983/solr/replication</str>
     <str name="confFiles">admin-extra.html,elevate.xml,mapping-FoldToASCII.txt,mapping-ISOLatin1Accent.txt,protwords.txt,schema.xml,scripts.conf,spellings.txt,stopwords_en.txt,stopwords.txt,synonyms.txt</str>
     <str name="pollInterval">00:00:20</str>
     </lst>
     </requestHandler>


We tell it that it's going to be a slave, then give it a masterUrl, which is the default admin port on the master Solr server. You can test this in a browser first to make sure it's all setup correctly. After this we'll turn on replication for the search core. Here that will be /var/lib/tomcat6/solr/cores/activity_logs/conf/solrconfig.xml

    
    <requestHandler name="/replication" class="solr.ReplicationHandler" >
     <lst name="slave">
     <str name="masterUrl">http://128.128.164.109:8080/solr/activity_logs/replication</str>
     <str name="confFiles">admin-extra.html,elevate.xml,mapping-FoldToASCII.txt,mapping-ISOLatin1Accent.txt,protwords.txt,schema.xml,scripts.conf,spellings.txt,stopwords_en.txt,stopwords.txt,synonyms.txt</str>
     <str name="pollInterval">00:00:20</str>
     </lst>
     </requestHandler>


Pretty much the same thing here, with the difference being the masterUrl, which is to the core we want it to replicate with. Again, check it in a browser to be sure everything is on the level. From here you could repeat the Slave section for as many other cores as you had, but here we'll just do this one and get it working first.


### Restart all nodes


After that we're ready for Apache Solr replication, restart both the master and slave nodes

    
    /etc/init.d/tomcat6 restart


and watch the logging on the slave node

    
    tail /var/log/tomcat6/*.log


During startup it will appear to freeze and not update the logs, this is normal, as it is doing its initial replication with the master. After it has done this and you've gotten the normal start verbiage in the logs, check that replication is working on the Slave node by visiting http://SLAVE_IP:8080/solr/activity_logs/admin Look for a link for 'Replication', click that and you'll have the stats on the index size, as well as when it was last replicated from the Master.

Questions? Leave a comment below and I'll be happy to push you along!
