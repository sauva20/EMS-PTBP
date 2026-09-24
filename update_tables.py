with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

with open('scratch_tables.blade.php', 'r', encoding='utf-8') as f:
    new_tables = f.read()

# Find index of line to insert before
idx = -1
for i, line in enumerate(lines):
    if '<div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end items-center gap-4 rounded-b-2xl">' in line:
        idx = i
        break

if idx != -1:
    lines.insert(idx, new_tables + '\n\n')

js_addon = '''
            // Matrix Logic for extra tables
            window.formatInputAndCalcRow = function(input, rowClass, totalId) {
                let v = input.value.replace(/[^0-9.]/g, '');
                if (v === '') {
                    input.value = '';
                } else {
                    let p = v.split('.');
                    p[0] = p[0].replace(/\\B(?=(\\d{3})+(?!\\d))/g, ',');
                    input.value = p.join('.');
                }

                let total = 0;
                document.querySelectorAll('.' + rowClass).forEach(el => {
                    let val = parseFloat(el.value.replace(/,/g, ''));
                    if (!isNaN(val)) total += val;
                });

                const totalEl = document.getElementById(totalId);
                if (totalEl) {
                    if (input.placeholder && input.placeholder.includes('.')) {
                        let decimals = input.placeholder.split('.')[1].length;
                        totalEl.textContent = total.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                    } else {
                        totalEl.textContent = total.toLocaleString('en-US');
                    }
                }
            };
            
            document.querySelectorAll('[class*="row-calc-"]').forEach(input => {
                if (input.value) {
                    const event = new Event('input', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
'''

for i, line in enumerate(lines):
    if 'window.reloadPage = function()' in line:
        lines.insert(i, js_addon + '\n')
        break

with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)
