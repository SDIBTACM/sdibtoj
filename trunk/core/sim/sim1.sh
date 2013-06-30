#!/bin/bash
EXTENSION=`echo "$1" | cut -d'.' -f2`
flag=0;
for i in ../data/$2/ac/*.$EXTENSION
do 
	sim=`sim_$EXTENSION -p $1 $i |grep ^$1|awk '{print $4}'`
        if [ ! -z $sim ] && [ $sim -eq 100 ]
        then
                 sim_s_id=`basename $i`
                 echo "$sim $sim_s_id" >sim
                 exit $sim

        fi
	if [ ! -z $sim ] && [ $sim -gt 50 ]
	then 
                 j=$i
                 max=$sim
                 flag=1; 
	fi
done
if [ $flag ]
then
                 sim_s_id=`basename $j`
                 echo "$max $sim_s_id" >sim
                 exit $max
fi


exit 0;
