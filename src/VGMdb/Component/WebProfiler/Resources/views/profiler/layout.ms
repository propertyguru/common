<div class="clear-fix">
  <div id="collector-wrapper">
    {{>@WebProfiler/profiler/header}}
    {{{content}}}
  </div>
  <div id="navigation">
    {{{toolbar}}}
    {{{searchbar}}}
  </div>
</div>
<script type="text/javascript">//<![CDATA[

    function toggleMenuPanels(state, doSave) {
        var leftIconPath = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAhCAYAAADOHBvaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4tJREFUeNrkl19IU1Ecx7fZJN2arhq2JUWJlEZL1MAm1B582GBErMJqtabzRRAqFAYa9OfFFx+KQEG3MkYRZkkEJUUwctVSKCJWJojL1ZiydJvLtNXW98AW1znv7t0CHzrw5Xe3nXM/O/f8zvf8LjcajXJWo/E4q9T+P/CaNMZw29rahBMTE3LyoaKi4m1zc/M865swSS6bzZbT09NzfG5urjYUCikikYiQ+ntWVlZQKBQ+F4lE/Var9Ta+CmcK5hsMhpNer/fiwsLCFiYzycnJGZfJZOctFss9uj+wInhwcDDfbDb3+Hy+I+msoVQqNZtMpnNlZWUhxuChoaENHR0djwOBwN5MEkgsFtsGBgY0uPzOJKtzu7q6rmcKJW12dlaJpbqGy7WpwPyWlpZGj8dzkMX9B+l+dLlcda2trbrEHbQEPDY2Jh0dHTWxgF5AxqsRz9J1cjqdlxDWrwTO7u3tNWK7SFhAL8e221WE7pU6Ytk2t7e311JnTQWLJycntWyhpCmVShXCKboBMJyjCKJlzgVzKJiamtrBAKoH1JoAvU+2MN2g6enp3QgboRnqjHl2u70kHA7zU0APpQMlze/357vd7gKyhang7GAwKEsx1gLoAwp0E8JdJtB4w1LK4sw4OAtWl51inBGwAxT/9iLUQb+ZgsHgJ4I5hYWFAQZjbYBrKPB+hGNM4DweL1pUVBRMzOpweXm5WyAQ/GIAfwj4GbZwiUTyIy8vby7eLw4mQH9paamX4VO7kgTeTTeguLjYg0AOjAgVTD58UygUb1i41l84ogGhka4z+rwi9p3MQHwajeYRjrNFlvBbiDfoOmFtQzU1NU/J5JKBA3w+/71er3/J8hA6kSKpOPX19c9w+YH4VDIwqRbG1Wr1HZVK5eX8o6bVaj9XV1cTk3HFcinpsUjs7AWKNwvWO5gpFI93pqmpyYxLB0neVBUIcaI90OHOzs7TfX19ErZA8niNRuNXnU53M2apTmiBSc0lgEoh9cjIiBYV5i6c1YxKYblc/rOhoeEdIin2nkCfoHk2VSYpV7ZCVdB+h8OxDyocHh4WoEJZskRwvUhVVVUIcldWVtrx1XPoNfQFWkynvCWzzIe2QSXQTmg7Od5QT+WinuagliaFnI8kJvQxJpJIATo34zJ8WyR/YF2sfBFDpKCPHyqLMUeajSVniJq9mYKXjUtwPdY34a7W+/EfAQYAZM5kh2zoNWsAAAAASUVORK5CYII=",
            rightIconPath = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAhCAYAAADOHBvaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4tJREFUeNrsl11IU2EYx91ytrnpmmy4TYNkqGhEJGiiabuQ2EAiZ1kZW9NtF4pgoBB40weJeCHVjReygVmuIhP6gLoJJNQGadhiJcLa2sb8KN2nrprbel7YYh7WzjnOsIsO/Hnes533/Z33PB/nOZRIJJK2GwflP/ifBg8MDGTOzs4eQeOCggJjb2+vH4aRvwGmyeXyZq/Xe9rv99eGQqHs+D+pVKqfxWJNZ2VlPdRoNPfFYnEgVTBNpVI1Op3OG4FAQETkDul0uo3P518dHh6+B6dB0uC5uTlWf3//zcXFRfV2fMjlcsfUarVGIpG4yYCZDQ0Nz10ulziVAGKz2W+7u7ulNTU1q0TAdKVSOWi1Wlt2InqFQuFTvV5/HoYbW+ICc116T0/PBQLQl0TBEB8nYddtKF6SgXNMJtM1nLUuTUxMSMFeIQqfn5+/vLCwIPgTOL2vr++sx+PJS7LGEEBvowHY60ThkII8iHIVDDMSgbMtFssZnDXkkKOS2AkZuM1mk4HhJAJzV1ZWDuHMZ4DGE8AVeODl5eVin8+XiwVT7HZ7rtvt3kfg5hPB74I5lWxSMBikTU5OlsSYMTAVHoWQRJYg+COA8+PgT8Dokk2CkiuM+fk3mMFg0EiAQ6AWgC3FfoCbOA5GlfRuGQwE3bPFxyKRyAvFPkIQeg6gY3HQerRpvIn5+fkerI9DUN58PB4vsA1oJ5hneFAmk7lZVlZmj704YuAwSrfCwkInzvyhBNBbRHxTWlqK3IJeGJvYdHLBQm9w5rfBNUqyUHRUVVW9A7Ma3eSWlwRK7np4/w6azWYWzjp6UDNRqEAg+DEyMtJJo9Eew+k37I59oI+tra2vIMjw1momkQFpCoViGqAfYOhJVLnQs7dWV1ePy2SyLzvV1EEjsCSVSh/A0BzfkWC3hpxv6Ojo0NbV1a2lCgW/eru6ulBRmQKt4TYCoIMg2ejo6EWdTpcXDodJQ5uamr62t7ffgSHy63tQgEjrkwkqBp0wGo2NWq32MNgMIsCioqJN6DRN5eXl43D6AsUNaJ1Ml7kXFRvQUVDtzMzMMYPBsB/EcjgcVEx7E66oqFivrKx0gFBKvkYuA6FY+b6d9hbVVTboAKgkKtTmcqHgM6G/TuNwOBvRFPmMmg3QJ5Alvlik0tCng1Bu50TznRV9Iuj4iaoeKkDRAPIlA6b67UTBlFvSi+zaR9svAQYA6+V18DvfH/8AAAAASUVORK5CYII=",
            menu = document.getElementById('navigation'), savedState = Sfjs.getPreference('menu/displayState'),
            displayState, elem, className;

        if (savedState == null) {
            savedState = 'block';
        }

        displayState = state || (savedState == 'block' ? 'none' : 'block');

        if (typeof doSave === 'undefined') {
            doSave = true;
        }

        document.getElementById('searchBar').style.display = displayState;
        //document.getElementById('adminBar').style.display = displayState;

        if (displayState == 'block') {
            Sfjs.removeClass(menu, 'collapsed-menu');
            Sfjs.removeClass(menu.parentNode.parentNode, 'collapsed-menu-parents');

            document.getElementById('minimizePanelIcon').src = leftIconPath;
        } else {
            Sfjs.addClass(menu, 'collapsed-menu');
            Sfjs.addClass(menu.parentNode.parentNode, 'collapsed-menu-parents');

            document.getElementById('minimizePanelIcon').src = rightIconPath;
        }

        if (doSave) {
            Sfjs.setPreference('menu/displayState', displayState);
        }

        try {
            canvasAutoUpdateOnThresholdChange(null);
        } catch (err) {

        }

        return false;
    }

    window.setTimeout(function() {
        if (document.getElementById('menu-profiler') == null) {
            return;
        }

        var menuItems = document.getElementById('menu-profiler').getElementsByTagName('LI'),
            elem, value, child, displayState = Sfjs.getPreference('menu/displayState');

        if (displayState == 'none') {
            toggleMenuPanels('none', false);
        }

        for (elem in menuItems) {
            if (typeof(menuItems[elem].children) != 'undefined' &&
                menuItems[elem].children.length > 0) {
                child = menuItems[elem].children[0]

                if (child.getAttribute('title') == '' ||
                    child.getAttribute('title') == null) {
                    value = child.text.replace(/^\s+/g, '').split('\n')[0].replace(/\s+$/g, '');
                    child.setAttribute('title', value);
                }
            }
        }
        document.getElementById('menu-profiler').style.opacity = '1';
        document.getElementById('collector-content').style.opacity = '1';
        document.getElementById('resume').style.opacity = '1';
    }, 25);

//]]></script>