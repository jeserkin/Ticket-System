<?php

SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = 'ticket_system' AND table_name = 'ts_system_settings';

?>