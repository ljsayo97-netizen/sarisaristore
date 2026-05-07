<?php
echo "An exception occurred:\n";
echo "Message: " . $message . "\n";
echo "File: " . $exception->getFile() . "\n";
echo "Line: " . $exception->getLine() . "\n";
echo "Trace:\n" . $exception->getTraceAsString() . "\n";
