# Advent of Code 2023

https://adventofcode.com/2023/

## Notes

##### Day 01

Went for regex which made life pretty simple, part two errored as there is an issue with shared letters which the sample data did not catch. Tweaked the replacement to leave start/end letters to solve it but wasted lots of time. I blame the examples, they all passed fine =/

##### Day 02

Used a good data structure so was both tasks were quite straight forward. Wrote code for a wrongly anticipated part 2, but it was even simpler in the end.

##### Day 03

Worked backwards from the found symbols then found adjacent digits to expand into full values. Probably over did the data structure.

##### Day 04

Calculated counts based on single card scores to and running tally avoid exponential growth.

##### Day 05

Brute forced part 2 in reverse to reduce search space, only takes a couple of minutes so didn't bother improving.

##### Day 06

Implemented a brute force approach as the numbers looks pretty small, optimised by finding first + last rather than searching all. Then reimplemented after using quadratic equations but it wasn't that much faster in really.

##### Day 07

Implemented with a custom comparison function with build in sort (PHP uses quicksort). Generated a hand value with type as the most significant bit and a base 13 representation of the cards following. Part 2 just needed jokers assigned to most common card and a tweak to hand strength values.

##### Day 08

Build a graph for the data and traversed it for length. Part two used LCM of individual lengths.

##### Day 09

Not much to say really. Simple part 1 and just reversed the values and ran again for part 2.

##### Day 10

Used line crossing counts to detect inside/outside for part 2, there's probably efficiency gain to be had there but it runs fast so left it as is.

##### Day 11

Minor off-by-one error in part 2 due to how I was increasing the blank space, but I was storing only galaxy positional data anyway so runs very fast. Spotted the Manhattan Distance so calculations were pretty simple.

##### Day 12

Nice recursion day. Included caching from the start so part 2 wasn't an issue.

##### Day 13

Fun task, made good use of PHP string and array functions for processing and collated mirror lines for each row, picking to common one. Part 2 took and columns that were 1 off and checked for smudge possibility.

##### Day 14

No issues, spent some time on part 2 optimising to move rocks in 1 step each and used serialisation for the cache identifier to allow state restoring to allow a single calculation at the end for final score.
