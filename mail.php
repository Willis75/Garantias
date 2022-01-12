<?php
exec(@mail( $to, $subject, $message, $headers ). " > /dev/null &");