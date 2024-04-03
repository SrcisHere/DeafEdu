const gujaratiInput = document.getElementById('gujaratiInput');
const virtualKeyboard = document.getElementById('virtualKeyboard');

// Gujarati keyboard layout
const gujaratiLayout = [
    'ક', 'ખ', 'ગ', 'ઘ', 
    'ચ', 'છ', 'જ', 'ઝ', 'ઞ',
    'ટ', 'ઠ', 'ડ', 'ઢ', 'ણ',
    'ત', 'થ', 'દ', 'ધ', 'ન',
    'પ', 'ફ', 'બ', 'ભ', 'મ',
    'ય', 'ર', 'લ', 'વ', 'શ',
    'ષ', 'સ', 'હ', 'ળ', 'ક્ષ',
    'જ્ઞ',
    'અ', 'આ', 'ઇ', 'ઈ', 'ઉ',
    'ઊ', 'એ', 'ઐ', 'ઓ', 'ઔ',
    'અં', 'ઋ', 'ૠ', 'ઍ', 'ઑ',
    , 'ૐ', 
    'ં', 'ઃ', 'ઁ', 'ા', 'િ',
    'ી', 'ુ', 'ૂ', 'ે', 'ૈ',
    'ો', 'ૌ','્','્ર',',','.',
];

// Create virtual keyboard
gujaratiLayout.forEach(char => {
    const key = document.createElement('div');
    key.className = 'key';
    key.textContent = char;
    key.addEventListener('click', () => appendToInput(char));
    virtualKeyboard.appendChild(key);
});

// Function to append clicked key to the input field
function appendToInput(char) {
    gujaratiInput.value += char;
}
