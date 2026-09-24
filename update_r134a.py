with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the fetching part
old_fetch = "$r407c = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R407C');"
new_fetch = "$r407c = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R407C');\n        $r134a = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R134A');"

if old_fetch in content:
    content = content.replace(old_fetch, new_fetch)
else:
    print("Failed to find old fetch")

# Replace the array mapping part
old_loop = "@foreach([$r22, $r407c] as $index => $cat)"
new_loop = "@foreach([$r22, $r134a, $r407c] as $index => $cat)"

if old_loop in content:
    content = content.replace(old_loop, new_loop)
else:
    print("Failed to find old loop")

# Replace rowspan="2" to rowspan="3"
old_rowspan = 'rowspan="2">Refrigerant</td>'
new_rowspan = 'rowspan="3">Refrigerant</td>'

if old_rowspan in content:
    content = content.replace(old_rowspan, new_rowspan)
else:
    print("Failed to find old rowspan")

with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Done")
