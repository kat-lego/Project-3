import unittest

class TestMethods(unittest.TestCase):
	def test_rev(self):
        	self.assertEqual(self.rev("TUNA"), "ANUT")
	def rev(self,Str):
		if len(Str)==0:return Str;
		else:return Str[len(Str)-1]+self.rev(Str[0:len(Str)-1])

if __name__ == '__main__':
    unittest.main()


