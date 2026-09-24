import re

with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

js_code = """
        function calcElectricityMatrix() {
            const matrix = document.getElementById('electricity-matrix');
            if (!matrix) return;
            const coalFactor = parseFloat(matrix.dataset.coalFactor) || 0;
            
            const inputs = matrix.querySelectorAll('.input-purchased-elec');
            const totalsCell = matrix.querySelectorAll('.cell-total-purchased');
            const percentCell = matrix.querySelectorAll('.cell-percentage');
            const adjCell = matrix.querySelectorAll('.cell-adjusted');
            const coalCell = matrix.querySelectorAll('.cell-purchased-coal');
            const co2Cell = matrix.querySelectorAll('.cell-co2-coal');
            
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            
            const elTotal = document.getElementById('total_purchased_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotal = document.getElementById('grand_total_purchased_elec');
            if(elGrandTotal) elGrandTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            let totalCoal = 0;
            let totalCo2 = 0;
            
            inputs.forEach((input, index) => {
                const val = parseFloat(input.value) || 0;
                if (totalsCell[index]) totalsCell[index].textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Simplified percentage
                const pct = total > 0 ? (val / total) : 0;
                if (percentCell[index]) percentCell[index].textContent = pct.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // For preview purposes, we just assume coal is the val (GET is calculated backend)
                const coal = val; 
                if (coalCell[index]) coalCell[index].textContent = coal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                const co2 = coal * coalFactor;
                if (co2Cell[index]) co2Cell[index].textContent = co2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                totalCoal += coal;
                totalCo2 += co2;
            });
            
            const totCoalEl = document.getElementById('total_purchased_coal');
            if(totCoalEl) totCoalEl.textContent = totalCoal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const totCo2El = document.getElementById('total_co2_coal');
            if(totCo2El) totCo2El.textContent = totalCo2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        function calcSolarMatrix() {
            const matrix = document.getElementById('solar-matrix');
            if (!matrix) return;
            const factor = parseFloat(matrix.dataset.factor) || 0;
            
            const inputs = matrix.querySelectorAll('.input-solar');
            const co2Cells = matrix.querySelectorAll('.cell-solar-co2');
            
            let total = 0;
            let totalCo2 = 0;
            
            inputs.forEach((input, index) => {
                const val = parseFloat(input.value) || 0;
                total += val;
                
                const co2 = val * factor;
                totalCo2 += co2;
                
                if (co2Cells[index]) co2Cells[index].textContent = co2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            });
            
            const elTotal = document.getElementById('total_solar_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elCo2 = document.getElementById('total_solar_co2');
            if(elCo2) elCo2.textContent = totalCo2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Initial calc
        setTimeout(() => {
            calcElectricityMatrix();
            calcSolarMatrix();
        }, 500);
"""

if 'calcElectricityMatrix()' not in content:
    new_content = content.replace('</script>\n    @endpush', js_code + '\n</script>\n    @endpush')
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Added JS code")
else:
    print("JS already exists")
