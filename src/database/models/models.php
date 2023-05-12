<?php
foreach(glob("src/database/models/*.php") as $file) {
    require_once $file;
}