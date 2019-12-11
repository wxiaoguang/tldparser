package tldparser

import "testing"

func testDomain(input, sub, main, tld string, t *testing.T) {
	s, m, td := ParseDomain(input)
	if td != tld {
		t.Errorf("should have TLD '%s', got '%s'", tld, td)
	} else if m != main {
		t.Errorf("should have Domain '%s', got '%s'", main, m)
	} else if s != sub {
		t.Errorf("should have Subdomain '%s', got '%s'", sub, s)
	}
}


func testFldSld(input, fld, sld1, sld2 string, t *testing.T) {
	f, s1, s2 := ParseDomainFldSld(ParseDomain(input))
	if f != fld {
		t.Errorf("should have FLD '%s', got '%s'", fld, f)
	} else if s1 != sld1 {
		t.Errorf("should have SLD1 '%s', got '%s'", sld1, s1)
	} else if s2 != sld2 {
		t.Errorf("should have SLD2 '%s', got '%s'", sld2, s2)
	}
}


func TestAll(t *testing.T) {
	testDomain("no_such", "", "", "", t)
	testDomain(".com", "", "", "com", t)
	testDomain(".no-such.com", "", "no-such", "com", t)

	testDomain("foo.com", "", "foo", "com", t)
	testDomain("zip.zop.foo.com", "zip.zop", "foo", "com", t)
	testDomain("au.com.au", "", "au", "com.au", t)
	testDomain("im.from.england.co.uk", "im.from", "england", "co.uk", t)
	testDomain("google.com", "", "google", "com", t)

	testDomain("a.ck", "", "", "a.ck", t)
	testDomain("b.a.ck", "", "b", "a.ck", t)
	testDomain("c.b.a.ck", "c", "b", "a.ck", t)

	testDomain("www.ck", "", "www", "ck", t)
	testDomain("1.www.ck", "1", "www", "ck", t)

	testFldSld("xxx", "", "", "", t)
	testFldSld("google.com.cn", "google.com.cn", "", "", t)
	testFldSld("b.google.com.cn", "google.com.cn", "b.google.com.cn", "", t)
	testFldSld("a.b.google.com.cn", "google.com.cn", "b.google.com.cn", "a.b.google.com.cn", t)

	testFldSld("b.google.jp", "google.jp", "b.google.jp", "", t)
	testFldSld("x.a.b.google.jp", "google.jp", "b.google.jp", "a.b.google.jp", t)
}
