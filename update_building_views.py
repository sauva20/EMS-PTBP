with open('resources/views/master/buildings/create.blade.php', 'r', encoding='utf-8') as f:
    create_content = f.read()

old_campus_input = '''<x-text-input id="campus" name="campus" type="text" class="mt-1 block w-full" :value="old('campus')" required autofocus placeholder="e.g. Campus 1" />'''
new_campus_input = '''<select id="campus" name="campus" class="mt-1 block w-full border-gray-300 focus:border-[#009B77] focus:ring-[#009B77] rounded-md shadow-sm" required autofocus>
                                    <option value="">-- Select Campus --</option>
                                    @foreach($campuses as $c)
                                        <option value="{{ $c->name }}" {{ old('campus') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>'''

if old_campus_input in create_content:
    create_content = create_content.replace(old_campus_input, new_campus_input)
    with open('resources/views/master/buildings/create.blade.php', 'w', encoding='utf-8') as f:
        f.write(create_content)
    print("Updated create.blade.php")
else:
    print("Failed to find campus input in create.blade.php")


with open('resources/views/master/buildings/edit.blade.php', 'r', encoding='utf-8') as f:
    edit_content = f.read()

old_campus_input_edit = '''<x-text-input id="campus" name="campus" type="text" class="mt-1 block w-full" :value="old('campus', $building->campus)" required placeholder="e.g. Campus 1" />'''
new_campus_input_edit = '''<select id="campus" name="campus" class="mt-1 block w-full border-gray-300 focus:border-[#009B77] focus:ring-[#009B77] rounded-md shadow-sm" required>
                                    <option value="">-- Select Campus --</option>
                                    @foreach($campuses as $c)
                                        <option value="{{ $c->name }}" {{ old('campus', $building->campus) == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>'''

if old_campus_input_edit in edit_content:
    edit_content = edit_content.replace(old_campus_input_edit, new_campus_input_edit)
    with open('resources/views/master/buildings/edit.blade.php', 'w', encoding='utf-8') as f:
        f.write(edit_content)
    print("Updated edit.blade.php")
else:
    print("Failed to find campus input in edit.blade.php")
