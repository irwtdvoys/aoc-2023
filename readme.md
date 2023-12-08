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
