package tldparser

import "testing"

func run(input, sub, main, tld string, t *testing.T) {
	s, m, td := ParseDomain(input)
	if td != tld {
		t.Errorf("should have TLD '%s', got '%s'", tld, td)
	} else if m != main {
		t.Errorf("should have Domain '%s', got '%s'", main, m)
	} else if s != sub {
		t.Errorf("should have Subdomain '%s', got '%s'", sub, s)
	}
}

func Test0(t *testing.T) {
	run("no_such", "", "", "", t)
	run(".com", "", "", "com", t)
	run(".no-such.com", "", "no-such", "com", t)

	run("foo.com", "", "foo", "com", t)
	run("zip.zop.foo.com", "zip.zop", "foo", "com", t)
	run("au.com.au", "", "au", "com.au", t)
	run("im.from.england.co.uk", "im.from", "england", "co.uk", t)
	run("google.com", "", "google", "com", t)

	run("a.ck", "", "", "a.ck", t)
	run("b.a.ck", "", "b", "a.ck", t)
	run("c.b.a.ck", "c", "b", "a.ck", t)

	run("www.ck", "", "www", "ck", t)
	run("1.www.ck", "1", "www", "ck", t)
}
