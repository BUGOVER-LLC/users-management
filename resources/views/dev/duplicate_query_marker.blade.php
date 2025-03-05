@if (app()->environment('local') && app()->has('debugbar') && app('debugbar')->isEnabled())
    @sqlDuplicate

    <style>
        div.phpdebugbar-widgets-sqlqueries li.phpdebugbar-widgets-list-item.phpdebugbar-widgets-sql-duplicate {
            background-color: #FFAC4694 !important;
        }
    </style>

    <script>
        window.toggleSqlDuplicate = function() {
            let a = document.createElement('a');
            a.textContent = ' | Toggle Duplicates |';
            a.onclick = function() {
                let els = document.querySelectorAll(
                    'div.phpdebugbar-widgets-sqlqueries \
                     li:not(.phpdebugbar-widgets-table-list-item)\
                     :not(.phpdebugbar-widgets-sql-duplicate)',
                );
                for (let el of els) {
                    // toggle visibility
                    if (el.style.display === 'none') {
                        el.style.display = null;
                    } else {
                        el.style.display = 'none';
                    }
                }
            };
            let target = document.querySelector('.phpdebugbar-widgets-sqlqueries .phpdebugbar-widgets-status');
            target.appendChild(a);
        };
    </script>
@endif
