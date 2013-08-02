function removeDuplicates(arr)
{
	//get sorted array as input and returns the same array without duplicates.
	var result=new Array();
	var lastValue="";
	
	for (var i=0; i<arr.length; i++)
	{
		var curValue=arr[i];
		if (curValue != lastValue)
		{
		   result[result.length] = curValue;
		}
		lastValue=curValue;
	}
	
	return result;
}

