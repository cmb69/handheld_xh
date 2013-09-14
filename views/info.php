<!-- Handheld_XH: about view -->
<h4><?php echo $this->l10n('syscheck_title');?></h4>
<ul style="list-style: none">
<?php foreach ($this->systemChecks() as $check => $state):?>
    <li>
        <img src="<?php echo $this->stateIconPath($state);?>" alt="<?php echo $state;?>"
            style="margin: 0; height: 1em; padding-right: 1em"/>
        <span><?php echo $check;?></span>
    </li>
<?php endforeach;?>
</ul>
<h4><?php echo $this->l10n('about');?></h4>
<img src="<?php echo $this->iconPath();?>" style="float: left; margin: 0 0 10px 0" alt="Plugin Icon"/>
<p>Version: <?php echo HANDHELD_VERSION;?></p>
<p>Copyright &copy; 2011 <a href="http://www.videopoint.co.uk/">Brett Allen</a><br/>
Copyright &copy; 2012-2013 <a href="http://3-magi.net/">Christoph M. Becker</a></p>
<p>Handheld_XH is powered by <a href="http://detectmobilebrowsers.com/">Detect
Mobile Browsers</a></p>
<p style="text-align: justify">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p style="text-align: justify">This program is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.</p>
<p style="text-align: justify">You should have received a copy of the GNU
General Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
<div style="clear: both"></div>