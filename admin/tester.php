<?php
$ipaddr = substr("127.0.0.1", strrchr("127.0.0.1", ".")-3);
echo str_replace(strrchr("127.0.0.1", "."), "", "127.0.0.1");