function suggestPWord() {
	// Helper function to get a random substring of a specific length
	const getRandomSubstring = (characters, length) => {
		let result = '';
		for (let i = 0; i < length; i++) {
			result += characters.charAt(Math.floor(Math.random() * characters.length));
		}
		return result;
	};

	const symbols = '~!@^*#</>';
	const capitals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const smalls = 'abcdefghijklmnopqrstuvwxyz';
	const numbers = '0123456789';

	// equivalent to substr(str_shuffle('~!@^*#</>'), 0, 1)
	const encryp_symbol = getRandomSubstring(symbols, 1);
	
	// equivalent to substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1)
	const encryp_capital = getRandomSubstring(capitals, 1);
	
	// equivalent to substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5)
	const encryp_small = getRandomSubstring(smalls, 5);
	
	// equivalent to substr(str_shuffle('~!@^*#</>'), 0, 1)
	const encryp_symbol2 = getRandomSubstring(symbols, 1);
	
	// equivalent to substr(str_shuffle('0123456789'), 0, 4)
	const encryp_numbr = getRandomSubstring(numbers, 4);
	
	// equivalent to substr(str_shuffle('~!@^*#</>'), 0, 1)
	const encryp_symbol3 = getRandomSubstring(symbols, 1);

	// Concatenate the parts
	const encryptforpw = encryp_symbol + encryp_capital + encryp_small + encryp_symbol2 + encryp_numbr + encryp_symbol3;
	
	return encryptforpw;
}

function suggestPincodexx() {
	// Helper function to get a random character from a string
	const getRandomChar = (characters) => {
		return characters.charAt(Math.floor(Math.random() * characters.length));
	};

	// Helper function to get a random substring of a specific length (like the previous example)
	const getRandomSubstring = (characters, length) => {
		let result = '';
		for (let i = 0; i < length; i++) {
			result += getRandomChar(characters);
		}
		return result;
	};

	const permitted_chars1 = '123456789'; // Numbers 1 through 9
	const permitted_chars2 = '0123456789'; // Numbers 0 through 9

	// PHP's substr(str_shuffle($permitted_chars1), 0, 1) gets 1 random character from '1-9'
	const part1 = getRandomSubstring(permitted_chars1, 1);

	// PHP's substr(str_shuffle($permitted_chars2), 0, 6) gets 6 random characters from '0-9'
	const part2 = getRandomSubstring(permitted_chars2, 6);

	// Concatenate the two parts. trim() is usually unnecessary when dealing with single characters and numbers.
	const combine = part1 + part2;

	return combine;
}

function randNmbrfive() {
	const getRandomChar = (characters) => {
		return characters.charAt(Math.floor(Math.random() * characters.length));
	};

	const getRandomSubstring = (characters, length) => {
		let result = '';
		for (let i = 0; i < length; i++) {
			result += getRandomChar(characters);
		}
		return result;
	};

	const permitted_chars1 = '0123456789';
	const permitted_chars2 = '0123456789';

	const part1 = getRandomSubstring(permitted_chars1, 1);

	const part2 = getRandomSubstring(permitted_chars2, 4);

	const combine = part1 + part2;

	return combine;
}