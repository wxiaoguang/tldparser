import collections.abc

if __name__ == '__main__':
    from tldparser_data import _tld_map
else:
    from .tldparser_data import _tld_map


__all__ = ['tld_parse_domain', 'tld_parse_domain_fld_sld']


def tld_parse_domain(domain):
    sub = ''
    main = ''
    tld = ''
    tm = _tld_map['ICANN DOMAINS']
    mtype = None
    for i in range(len(domain) - 1, -2, -1):
        if i == -1 or domain[i] == '.':
            s2 = domain[i + 1:]
            if s2 in tm or mtype == 2:
                mtype = tm.get(s2, 1)
                if mtype == 0:
                    continue
                if mtype == 3:
                    break

                s1 = domain[:i] if i >= 0 else ''
                tld = s2
                p = s1.rfind('.')
                if p != -1:
                    sub = s1[:p]
                    main = s1[p+1:]
                else:
                    sub = ''
                    main = s1
            else:
                break
    return sub, main, tld


def tld_parse_domain_fld_sld(domain):
    sub, main, tld = domain if isinstance(domain, collections.abc.Sequence) and not isinstance(domain, str) \
        else tld_parse_domain(domain)

    fld = ''
    sld1 = ''
    sld2 = ''
    if main != '':
        fld = main + '.' + tld
        if sub:
            p = sub.rfind('.')
            s = sub[p+1:] if p != -1 else sub
            sld1 = '{}.{}.{}'.format(s, main, tld)

            if p != -1:
                p = sub.rfind('.', 0, p-1)
                s = sub[p + 1:] if p != -1 else sub
                sld2 = '{}.{}.{}'.format(s, main, tld)

    return fld, sld1, sld2


def _assert_eqauls(value, expected, msg=''):
    if value != expected:
        print("assert failed, value:{}, expected:{}, msg={}".format(value, expected, msg))


if __name__ == '__main__':

    _assert_eqauls(tld_parse_domain('a.ck'), ('', '', 'a.ck'), "*.ck")
    _assert_eqauls(tld_parse_domain('b.a.ck'), ('', 'b', 'a.ck'))
    _assert_eqauls(tld_parse_domain('c.b.a.ck'), ('c', 'b', 'a.ck'))
    _assert_eqauls(tld_parse_domain('www.ck'), ('', 'www', 'ck'), "*.ck excludes www.ck")
    _assert_eqauls(tld_parse_domain('1.www.ck'), ('1', 'www', 'ck'))

    _assert_eqauls(tld_parse_domain('no_such'), ('', '', ''))
    _assert_eqauls(tld_parse_domain('google.com.cn'), ('', 'google', 'com.cn'))
    _assert_eqauls(tld_parse_domain('a.b.google.jp'), ('a.b', 'google', 'jp'))

    _assert_eqauls(tld_parse_domain('test.co.za'), ('', 'test', 'co.za'))
    _assert_eqauls(tld_parse_domain('test.nosuch.za'), ('', '', ''))

    _assert_eqauls(tld_parse_domain_fld_sld('xxx'), ('', '', ''))
    _assert_eqauls(tld_parse_domain_fld_sld('b.google.com.cn'), ('google.com.cn', 'b.google.com.cn', ''))
    _assert_eqauls(tld_parse_domain_fld_sld('a.b.google.com.cn'), ('google.com.cn', 'b.google.com.cn', 'a.b.google.com.cn'))
    _assert_eqauls(tld_parse_domain_fld_sld('x.a.b.google.jp'), ('google.jp', 'b.google.jp', 'a.b.google.jp'))

    print('python test done')
