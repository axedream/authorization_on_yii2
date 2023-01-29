<?php

?>
<ul class="nav nav-tabs" style="margin-bottom: 10px;">

    <li class="nav-item">
        <a class="nav-link <?= ($page == 'android') ? 'active' : ''?>" href="/fb_sharing/index">
            <span class="nav-link-text"><!--<i class="fas fa-user-circle-o"></i>--> Android</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($page == 'ios') ? 'active' : ''?>" href="/fb_sharing/ios">
            <span class="nav-link-text"><!--<i class="fas fa-bullhorn"></i>--> iOS</span>
        </a>
    </li>

</ul>
