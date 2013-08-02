//To remove an item from a simple array
function removeItem(originalArray, itemToRemove) {
	var j = 0;
	while (j < originalArray.length) {
		if (originalArray[j] == itemToRemove) {
			originalArray.splice(j, 1);
		} else { j++; }
	}
	return originalArray;
}

//To remove dublicate numbers (ints) in a simple array
function Numsort (a, b) {
  return a - b;
}