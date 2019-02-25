def rev(Str):
	if len(Str)==0:return Str;
	else:return Str[len(Str)-1]+rev(Str[0:len(Str)-1])
x=str(input())
print(rev(x));
