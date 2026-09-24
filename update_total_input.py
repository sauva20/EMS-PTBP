import re

with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the HTML for the Total purchased electricity row
old_html_row = """                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Total purchased electricity (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c1_total_purchased">0.00</td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c2_total_purchased">0.00</td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_purchased_elec">0.00</td>
                </tr>"""

new_html_row = """                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Total purchased electricity (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-1 border border-gray-300 text-center bg-gray-50">
                            <input type="text" id="inp_c1_total_purchased" class="w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="6,095,551.00" oninput="calcElectricityMatrix()">
                        </td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-1 border border-gray-300 text-center bg-gray-50">
                            <input type="text" id="inp_c2_total_purchased" class="w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="4,395,110.00" oninput="calcElectricityMatrix()">
                        </td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_purchased_elec">0.00</td>
                </tr>"""

content = content.replace(old_html_row, new_html_row)


# Update the JS calculation
# Instead of: campusPurchased[campus] += val;
# We read it from the inputs!

old_js_block = """            let total = 0;
            let campusPurchased = { 'c1': 0, 'c2': 0, 'other': 0 };
            
            inputs.forEach(input => {
                const val = parseFloat(input.value.replace(/,/g, '')) || 0;
                total += val;
                const campus = input.dataset.campus;
                if(campus) campusPurchased[campus] += val;
            });
            
            const elTotal = document.getElementById('total_purchased_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotal = document.getElementById('grand_total_purchased_elec');
            if(elGrandTotal) elGrandTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elC1TotPurchased = document.getElementById('c1_total_purchased');
            if (elC1TotPurchased) elC1TotPurchased.textContent = campusPurchased['c1'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elC2TotPurchased = document.getElementById('c2_total_purchased');
            if (elC2TotPurchased) elC2TotPurchased.textContent = campusPurchased['c2'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});"""


new_js_block = """            const inpC1TotPurchased = document.getElementById('inp_c1_total_purchased');
            const inpC2TotPurchased = document.getElementById('inp_c2_total_purchased');
            const c1TotPurchased = inpC1TotPurchased ? parseFloat(inpC1TotPurchased.value.replace(/,/g, '')) || 0 : 0;
            const c2TotPurchased = inpC2TotPurchased ? parseFloat(inpC2TotPurchased.value.replace(/,/g, '')) || 0 : 0;
            const campusPurchased = { 'c1': c1TotPurchased, 'c2': c2TotPurchased, 'other': 0 };
            
            let total = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value.replace(/,/g, '')) || 0;
                total += val;
            });
            
            const elTotal = document.getElementById('total_purchased_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotal = document.getElementById('grand_total_purchased_elec');
            if(elGrandTotal) elGrandTotal.textContent = (c1TotPurchased + c2TotPurchased).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});"""

content = content.replace(old_js_block, new_js_block)

with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Updated manual input")
