#!/usr/bin/perl -w
###
# $Id: CSV.pm,v 1.10 2001/08/19 01:42:33 christopher Exp $
#
# Distributed under the GNU Lesser General Public License v2.1.  See the
# accompanying lgpl.txt file for the license text; if the file was missing
# you may always obtain a copy from http://www.fsf.org/.
#
# This program is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# General Public License for more details.
#
# Copyright (c)2001 Christopher Rath <Christopher@Rath.ca> and
#    Mark Mielke <Mark@Mielke.cc>
###

###
# POD starts.
#

=head1 NAME

CSV.pm - a module providing manipulation routines for comma separated value (CSV) records

=head1 SYNOPSIS

    use CSV;

    %fieldLayout = CSVinit($firstRecord);
    if (! CSVvalidate(%CSVfields, I<field list>)) {
        die "Fields missing."};
    }
    while (<INPUT>) { 
        @record = CSVsplit($_);
	I<process the records>;
      }

=head1 LICENSE

Copyright (c)2001 Christopher Rath <C<Christopher@Rath.ca>> and Mark Mielke
<C<Mark@Mielke.cc>>.

Distributed under the GNU Lesser General Public License v2.1.  See the
accompanying C<lgpl.txt> file for the license text; if the file was missing you
may always obtain a copy from C<http://www.fsf.org/>.

=head1 WARRANTY

This program is distributed in the hope that it will be useful, but B<without
any warranty>; without even the implied warranty of B<merchantability> or
B<fitness for a particular purpose>.  See the GNU General Public License for
more details.

=head1 DESCRIPTION

This module defines some functions for reading comma separated value (CSV)
files (e.g., Remedy-ARS generated files).  The module also allows some a
delimiter other than a comma to be used, or a selection of delimiters to be
used (e.g., comma and semicolon).

The B<CSVinit(>I<$firstRecord>B<)> function is used to parse the initial record
of a CSV file; which by definition will contain the field names for each field
in the CSV records which follow in the file.  This initial record is itself a
CSV record.

The B<CSVvalidate(>I<\%output_from_CSVinit>B<,> <I<field_list>>B<)> function
is used to verify that the incoming file contains the full list of fields
specified in the I<field_list> provided to the function.

The B<CSVsplit(>I<$rawRecord>B<)> function splits a line, passed as
I<$rawRecord> (a CSV record), and returns it split into fields; sort-of like
B<split()>.

The B<$CSV::Delimiters> variable can be set to specify an alternate, or set of
alternate, field delimiters.  In general, it should be specified as a B<local>.

The B<CSVjoin(>I<@fields_to_join>B<)> function takes an array of fields and
turns them into a comma separated value record.  If B<$CSV::Delimiters> has
been used to specify multiple delimiters then B<CSVjoin()> will use the first
delimiter of the set for creation of CSV records.

=head1 USAGE

A sample application (Demo_App) of this module has been provided in order to
demonstrate how it can be used.  Some additional usage notes follow.

=head2 CSV::Delimiters

    local $CSV::Delimiters = ",;";
    %fieldLayout = CSVinit($firstRecord);
    @Fields = CSVsplit($aRawRecord);

This will cause either of 'B<,>' or 'B<;>' to be valid delimiters, even if
mixed.  B<local()> is used to temporarily override the value, instead of
permanently overriding the value.

B<CSVjoin()> will prefer the first character in the Delimiters string, or the old
behaviour of a ',' if the Delimiters string is not defined.

If you peek at the code, it actually implements the split core twice.  The
first with B<$CSV::Delimiters>, and the second with 'B<,>' hard-coded.  This is
to maintain efficiency for code that does not make use of a dynamic set of
delimiter characters.

=head2 CSVinit()

B<CSVinit()> is used to initialize the data structures for further CSV parsing.
The function scans the input record passed to it.  If it contains data, then
that data is used to define the field titles.  If no data is passed then some
default column data is used instead.  The function returns an associative array
containing field names and the corresponding field number.

=head3 Parameters

[1] $rawRecord - Record defining field layouts.

=head3 Returns

Returns an associative array of field layout.

=head2 CSVvalidate()

B<CSVvalidate()> is used to validate a set of field names.  A list of fields is
validated against the associative array previously built by a call to
CSVinit().  Returns true or false.

=head3 Parameters

[1] \%fields - the fields as built by CSVinit(). (reference)

[?] $...     - a list of field names to check.

=head3 Returns

Returns 0 if any single field is not found in %fields; otherwise 1.

=head2 CSVjoin()

B<CSVjoin()> is used to join CSV data.

=head3 Parameters

Context 1:

    [1] \@fields  - An array of fields to join into a CSV.
    [2] "minimum"|"quoteall" - Defaults to "minimum", define 
            whether fields must be quoted when not req'd.

Context 2:

    [?] $... - An array of fields to join into 
            a CSV.

=head3 Returns

Returns a string which may be "un"join'ed using CSVsplit().
except in the case of a newline contained within a field)

=head2 CSVsplit()

B<CSVsplit> is used to split CSV data, just like Perl's own split() does.

=head3 Parameters
    [1] $rawRecord - the record to split.

=head3 Returns

Returns an array of values split out of $rawRecord.

=head1 TESTING

Put the following lines into a file to test the comma-based parsing of this
module:

    #!/usr/bin/perl -w

    use CSV;

    while (<DATA>) {
        print join(":", CSVsplit($_)), "\n";
    }

    __DATA__
    "One from FAQ will do","sec""ond"
    "But, not","3rd""","or","""fifth"
    "don't","forget","fourth"",""","and"  ,  """,""sixth".
    "Of course, it doesn't solve",everything,8,N,1
    "Is there a new emacs   perl-mode?"
    "Tom","tom@fiction.org"
    Empty fields needed,,"and "wanted

    Some,"",boundary  ",cases"",  ,too
    Grok this\, Spok!
    "Didn't you notice\! in it \{the spec\}"
    "Multi-
    line",test

This will produce the following output:

    One from FAQ will do:sec"ond
    But, not:3rd":or:"fifth
    don't:forget:fourth",":"and"  :  """:""sixth".
    Of course, it doesn't solve:everything:8:N:1
    Is there a new emacs   perl-mode?
    Tom:tom@fiction.org
    Empty fields needed::"and "wanted

    Some::boundary  ":cases"":  :too
    Grok this\: Spok!
    Didn't you notice\! in it \{the spec\}
    "Multi-
    line":test

=head1 CSV SPECIFICATION

This section attempts to define CSV records and files in a fairly rigorous
fashion.  The point behind this is to make this module usable without having to
read and understand the source code.

=head2 CSV Records

The basic idea behind a CSV record is this: literal field values are
delimited by commas.  The immediate complication that arises is, of
course, ``What should be done when a comma must appear within a
field?''  Within the bounds of current practice, there are two
immediate solutions to this complication:

=over 4

=item 1.

Use a predefined escape character to tag commas which appear within fields.

=item 2.

Allow quotation marks to enclose a field and _protect_ a comma appearing within
a field.

=back

As with all work-arounds, these "immediate solutions" have complications of
their own (these secondary complications are numbered the same as their primary
counterparts):

=over 4

=item 1.

Escape characters must themselves be escaped in order to appear as a value
within a field (e.g., if a literal comma is expressed as ``\,'', then a literal
backslash must appear as ``\\'').

=item 2.

Quotation marks must somehow be protected if they are to appear as a literal
character within a field.

=back

Given that the essence of CSV files is simplicity, I have decided to reject
I<all> escape and escaped characters with the exception of quoation marks
appearing within quotation marks.  That is, the case of the escaped comma has
been rejected from this specification.

Within the context of Perl, the L<string(3)> library and the UNIX shells, an
additional level of complexity is added to this equation when we begin to ask,
``What is the meaning or significance of whitespace within a CSV record?''  The
meaning of whitespace is a key technical detail which must be accounted for in
both the specification and its implementation; otherwise, everyone's
implementation will produce semi-random results based upon that implementors
opinion regarding whitespace.

=head2 Semi-Formal CSV Record Specification

This specification uses the syntax described in S<Appendix A> of the first
edition of O'Reilly's Programming Perl book (i.e., the S<Perl 4> camel book).

    CSV_RECORD ::= (* FIELD DELIM *) FIELD REC_SEP

    FIELD ::= QUOTED_TEXT | TEXT

    DELIM ::= `,'

    REC_SEP ::= `\n'

    TEXT ::= LIT_STR | ["] LIT_STR [^"] | [^"] LIT_STR ["]

    LIT_STR ::= (* LITERAL_CHAR *)

    LITERAL_CHAR ::= NOT_COMMA_NL

    NOT_COMMA_NL ::= [^,\n]

    QUOTED_TEXT ::= ["] (* NOT_A_QUOTE *) ["]

    NOT_A_QUOTE ::= [^"] | ESCAPED_QUOTE

    ESCAPED_QUOTE ::= `""'

=head2 Notes

This specification does not grant any special status to whitespace characters.
This means that B<I<all whitespace is part of some field value.>>

The B<TEXT> non-terminal is attempting to express the cases where quotation
marks exist but do not completely encapsulate the field value; in cases like
this, the quotation marks should be treated as literal characters making up
part of the field value.

One ambiguity exist in this specification that I have been unable to properly
express.  The case of a field with the value ,abc""de,.  Should the double
quotation marks be treated as an escaped quotation mark or as two quotation
marks?  I believe that occurences of "" should be treated as escaped quotation
marks I<only> within a quoted string.

The B<LITERAL_CHAR> non-terminal exists partially as a place-holder.  Escaped
characters may be easily accomadated by this specification at a later date by
OR-ing them to the right side of B<LITERAL_CHAR>.

Some of the non-terminals exist solely as documentation/reading aids.  The
B<NOT_A_COMMA_NL> is one example of this case; its name helps express the
meaning of the regex (which should assist other readers of this document to
detect errors in the specification).

The B<ESCAPED_QUOTE> non-terminal includes the PASCAL-like case of ``""'' and
excludes the more traditional UNIX ``\\"''.  This is not my preference; I have
included it here because I know there exists at least one commercial tool that
produces CSV records containing the PASCAL-like construct and not the UNIX-like
one.

=head1 AUTHORS

Christopher Rath (C<christopher@rath.ca>) wrote the CSV specification and
everything in the module except the essential snippet of code that actually
does the work :).

Mark Mielke (C<mark@mielke.cc>) took the specification and wrote the essential
piece of code that actually breaks the CSV records into its constituent fields.
He also took the initial .pl version and .pm'ed it (this only makes sense,
since this module is only usable in perl5).

=head1 BUGS

=head2 Not Thread-Safe

This module, and hence B<CSVinit()>/B<CSVsplit()>, is not thread-safe or
re-entrant.

=head2 Fields Spanning Lines

This module currently fails in one of the test-cases, although the test output
listed herein, above, has been constructed to show the actual output of this
module, as opposed to the correct output:

    "Multi-
    line",test

This is due to the fact that perl is reading one line at a time with:

    while (<DATA>) { ... }

So the first line is read ("Multi-) and evaluated. The _second_ time around the
loop the second line (line",test) is read and evaluated.  There is no
workaround available, this is simply a limitation of the module.

=head1 VERSION

The RCS identifier for this module is
S<$Id: CSV.pm,v 1.10 2001/08/19 01:42:33 christopher Exp $>.

=cut

#
# End of POD.
###

package CSV;
require 5.000;
require Exporter;
$VERSION = 2.00;

@ISA = qw(Exporter);
@EXPORT = qw(CSVinit CSVvalidate CSVjoin CSVsplit);

use strict;

$CSV::Delimiters = undef;

###
# Setup Variables.
#
# CSVDefaultColumnMapping - this contains a dummy record; just in case the
#                           user calls us with nothing.
#
my(%CSVDefaultColumnMapping) = ("" => -1);

###
# CSVinit() - initialize data structures for further CSV parsing.
#
# This function scans the input record passed to it.  If it contains data, then
# that data is used to define the field titles.  If no data is passed then some
# default column data is used instead.  The function returns an associative
# array containing field names and the corresponding field number.
#
# Parameters;
#	[1] $rawRecord - Record defining field layouts.
#
# Returns:
#	Associative array of field layout.
###
sub CSVinit
{
    my @fields = CSVsplit(@_);
    my %columns;
    my $i = 0;

    if ($#fields >= 0) {
	# Build associative array with field -> column_number mapping.
	map { $columns{$_} = $i++ } @fields;
	%columns;
    } else {
	%CSVDefaultColumnMapping;
    }
}


### 
# CSVvalidate() - validate the field names passed in.
# 
# A list of fields is validated against the associative array previously built
# by a call to CSVinit().  Returns true or false.
# 
# Parameters: 
#       [1] \%fields - the fields as built by CSVinit(). (reference)
#	[?] $...     - a list of field names to check.
# 
# Returns: 
#       0 if any single field is not found in %fields; otherwise 1.
### 
sub CSVvalidate
{
    my $fields = shift;		# pass by reference!
    my $field;

    foreach $field (@_) {
	exists $fields->{$field} || return 0;
    }

    1;
}


###
# CSVjoin() - join CSV data.
#
# Parameters:
#    Context 1:
#          [1] \@fields  - An array of fields to join into a CSV.
#          [2] "minimum"|"quoteall" - Defaults to "minimum", defines
#                          whether fields must be quoted when not req'd.
#    Context 2:
#          [?] $...      - An array of fields to join into a CSV.
#
# Returns:
#        returns a string which may be "un"join'ed using CSVsplit().
#           (except in the case of a newline contained within a field)
###
sub CSVjoin
{
    my($fields, $mode) = ref($_[0]) ? @_ : \@_;
    my($quoteall, @quotedfields, $quotedfield);
    local $_;

    $mode = 'minimum' if !defined($mode);
    $quoteall = ($mode eq 'quoteall') ? 1 : 0;

    foreach (@$fields) {
	if ($quoteall || /[\",\n]/) {
	    ($quotedfield = $_) =~ s/\"/""/g;
	    push(@quotedfields, qq{"$quotedfield"});
	} else {
	    push(@quotedfields, $_);
	}
    }

    join((defined($CSV::Delimiters) ? substr($CSV::Delimiters,0,1) : ','),
         @quotedfields);
}


###
# CSVsplit() - split CSV data, just like Perl's own split() does.
#
# See description of record layout at top of this module.
#
# Parameters:
#	[1] $rawRecord - the record to split.
#
# Returns:
#	An array of values split out of $rawRecord.
###
sub CSVsplit
{
    local $_ = $_[0];
    my(@fields, $t);

    # According to the specs, a new-line is a field terminator not a field
    # delimiter. This only becomes an issue at the end of a string where
    # a new-line implies end of field, not a field delimiter. By stripping
    # the last optional new-line we allow all remaining new-lines to be
    # considered field delimiters.
    chomp;

    ###
    # Only build the array if at least one field exists. (if at least one
    # character exists, by definition, at least one field exists)
    #
    if (length($_) > 0) {
        ###
	# Break the record into fields.
	# Field Delimiter == /[,\n]/   (See comment above: According...)
        my $expecting;
        if (defined $CSV::Delimiters) {
            while (m`(?=.)((?:"((?:""|[^"]+)*)")?([^$CSV::Delimiters]*))([$CSV::Delimiters]?)`sg) {
                $expecting = $4;
                if (defined($2) && !length($3)) {
                    ($t = $2) =~ s/""/\"/g;
                    push(@fields, $t);
                } else {
                    push(@fields, $1);
                }
            }
        } else {
            while (m`(?=.)((?:"((?:""|[^"]+)*)")?([^,]*))(,?)`sg) {
                $expecting = $4;
                if (defined($2) && !length($3)) {
                    ($t = $2) =~ s/""/\"/g;
                    push(@fields, $t);
                } else {
                    push(@fields, $1);
                }
            }
        }
        push(@fields, '') if $expecting;
    }

    @fields;
}

1;

###
# End of package.
###

# Local Variables:
#       fill-column: 79
# End:
