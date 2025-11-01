{
  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-25.05";
    flake-utils.url = "github:numtide/flake-utils";
    # For access to PHP 7
    phps.url = "github:fossar/nix-phps";
  };

  outputs =
    {
      self,
      nixpkgs,
      flake-utils,
      phps,
    }:
    flake-utils.lib.eachDefaultSystem (
      system:
      let
        pkgs = import nixpkgs {
          inherit system;
          overlays = [ phps.overlays.default ];
        };
        php74Composer = pkgs.php82Packages.composer.override {
          php = pkgs.php74;
        };
      in
      {
        devShell = pkgs.mkShell {
          buildInputs = [
            pkgs.php74.packages.composer
            pkgs.php74
            pkgs.nixpkgs-fmt
          ];
        };
        formatter = pkgs.nixfmt-tree;
      }
    );
}
