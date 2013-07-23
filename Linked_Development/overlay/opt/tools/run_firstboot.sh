for SCRIPT in /usr/lib/inithooks/firstboot.d/*
    do
        if [ -f $SCRIPT -a -x $SCRIPT ]
        then
            $SCRIPT
        fi
    done