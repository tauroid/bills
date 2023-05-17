Bills
=====

Bill splitting website written with Laravel and Inertia.js.

Still in development but works well as it is.

![Screenshot_2023-05-17_20-23-38](https://github.com/tauroid/bills/assets/4631050/7ac0b8a9-70c4-4c4b-ac7d-b7461c32f088)

# Usage

A 'settlement' is a group of transactions at the end of which all participants want to have received the same amount of money and goods/services as they have given out.

To help with this, this app shows the current status of every participant, in a table at the top of the settlement's page. The status is simply the sum of that participant's contributions to the settlement's transactions, minus the sum of what they've received. To resolve a settlement, those who are owing should compensate those who are owed, until everyone's sum is 0 (they are even).

## Placeholders

A lot of the time, participants in a transaction won't be likely to make an account on this app. That's fine - to keep track of them, create a placeholder user. If they make an account later, it can be linked to the placeholder so that the transactions of the placeholder contribute to the user's total.

## Inconsistency

Because this app keeps track of transaction totals as well as contributions and receipts, it's possible for the information entered to be inconsistent (amount given =/= amount moved =/= amount received). The app will in this case put a warning symbol on the offending transaction, as well as the settlement, to signify that the participant statuses are not to be considered accurate until the inconsistency is resolved.

## Percentages and shares

As well as absolute amount in currency (currently only GBP), amounts can be entered in percentages of the transaction total, as well as in shares. Shares are equal divisions of the remainder after absolute amounts and percentages are deducted from the total. For example: if the total is £10, A paid 50%, B paid one share and C paid two shares, then A paid £5, B paid £1.67 and C paid £3.33 (modulo the penny).

Contributions and receipts of a transaction are set to one share by default.

# Development

If you have Nix and Docker installed, and [flakes enabled](https://nixos.wiki/wiki/Flakes#Enable_flakes), then you can spin up a local server (including database) at <http://localhost:8000> by running `nix develop`. Tested recently on macOS and Linux as of May 2023.

It will however take ~20 mins the first time, download a lot of files to your Nix store, and take up ~2GB of space (for the packages and then the Docker image).

While the Docker image is running, the database is kept synced to `db.sql` - you might want this or not, but just FYI.

## macOS

As the Docker image is x86\_64-linux, you'll need a cross builder (this will take up a good deal more space because it runs in a VM).

This repo has been tested with [darwin.builder](https://nixos.org/manual/nixpkgs/unstable/#sec-darwin-builder). Follow the instructions there, and in the `builders = ssh-ng://...` line, of `nix.conf`, replace the second `-` after `${MAX_JOBS}` with `kvm`.

# Deployment

See the relevant documentation for deploying Laravel and Inertia.js apps. The database schema is contained in `db.sql`, along with test data. At some point I'll separate the schema and the test data.

The .env file in this repository dictates the username and password for the dev database. This should ALWAYS BE CHANGED for any deployment.

