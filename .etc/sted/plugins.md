## Browsing Available Plugins

To browse available plugins, run `gem list --remote vagrant-`.
- vagrant plugin install "plugin-name"

## Disclaimers

Before Vagrant 1.4.2 there are two distinct names for each one of the plugins that you need to know:
- rubygem name (used for installing plugin, via `$ vagrant plugin install rubygem-plugin-name`)
- internal name (used in Vagrantfile, via `Vagrant.has_plugin?('Internal_Plugin_Name')` )

With Vagrant 1.4.2 and later you just need to know the **rubygem name of the plugin** that will work either for `$ vagrant plugin install` and for `Vagrant.has_plugin?()`.

The following list has both names in this order: rubygem name /internal name/

Plugins listed on this page are not "official" or supported in any way. If there is a plugin you wish to add to this list, please open an issue on this repo and a maintainer will gladly add it to the list.

## Chef

* [vagrant-berkshelf](https://github.com/RiotGames/vagrant-berkshelf) /berkshelf/
* [vagrant-butcher](https://github.com/cassianoleal/vagrant-butcher) /vagrant-butcher/
* [vagrant-librarian-chef](https://github.com/jimmycuadra/vagrant-librarian-chef) /vagrant-librarian-chef/
* [vagrant-librarian-chef-nochef](https://github.com/emyl/vagrant-librarian-chef-nochef) /vagrant-librarian-chef-nochef/
* [vagrant-omnibus](https://github.com/schisamo/vagrant-omnibus) /vagrant-omnibus/
* [vagrant-ohai](https://github.com/avishai-ish-shalom/vagrant-ohai) /vagrant-ohai/
* [vagrant-templated](https://github.com/afaundez/vagrant-templated) /vagrant-templated/ Init with custom Vagrantfile and Berksfile

## Puppet

* [vagrant-librarian-puppet](https://github.com/mhahn/vagrant-librarian-puppet) /vagrant-librarian-puppet/
* [vagrant-puppet-install](https://github.com/petems/vagrant-puppet-install) /vagrant-puppet-install/
* [vagrant-r10k](https://github.com/jantman/vagrant-r10k) /vagrant-r10k/ -- Deploys puppet modules using [r10k](https://github.com/adrienthebo/r10k) from a Puppetfile.

## Local Domain Resolution

### `/etc/resolver` approach
* [landrush](https://github.com/phinze/landrush) works for Guest and Host (using [dnsmasq](http://www.thekelleys.org.uk/dnsmasq/doc.html))
* [vagrant-dns](https://github.com/BerlinVagrant/vagrant-dns) (using [rubydns](http://www.codeotaku.com/projects/rubydns/index.en))
* [vagrant-dnsmasq](https://github.com/mattes/vagrant-dnsmasq) (using [dnsmasq](http://www.thekelleys.org.uk/dnsmasq/doc.html))

### `/etc/hosts` approach
* [vagrant-hostmanager](https://github.com/smdahlen/vagrant-hostmanager)
* [vagrant-hosts-provisioner](https://github.com/mdkholy/vagrant-hosts-provisioner) -- A Vagrant provisioner for managing the /etc/hosts file of the host and guest machines.
* [vagrant-hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater)
* [vagrant-multi-hostsupdater](https://github.com/SEEK-Jobs/vagrant-multi-hostsupdater)
* [vagrant-hosts](https://github.com/adrienthebo/vagrant-hosts)
* [vagrant-flow](https://github.com/DemandCube/vagrant-flow) -- A seamless development to production workflow with Ansible - Published ansible inventory files for multiple hosts, does local domain resolution too

### Registrar approach
* [vagrant-dns-updater](https://github.com/blueicefield/vagrant-dns-updater) -- A Vagrant plugin that allows you to automatically configure a subdomain with the IP of your vagrant instance using your registrar API. Only the registrar OVH is supported for the moment.

### Route53
* [vagrant-aws-dns](https://github.com/nasskach/vagrant-aws-dns) -- A Vagrant plugin that allows you to set up route53 records for instances created using vagrant-aws provider.

## Guests

* [vagrant-atomic](https://github.com/projectatomic/vagrant-atomic/) -- Adds a Vagrant guest type for [Atomic Host](http://www.projectatomic.io/).
* [vagrant-alpine](https://github.com/maier/vagrant-alpine) -- Vagrant plugin for [Alpine Linux](http://alpinelinux.org/) Guest.
* [vagrant-vyos](https://github.com/higebu/vagrant-vyos) -- [VyOS](http://vyos.net/) guest support for Vagrant
* [vagrant-guests-clearlinux](https://github.com/AntonioMeireles/vagrant-guests-clearlinux) -- Vagrant plugin for [Clear Linux](https://clearlinux.org).

## Providers

* [vagrant-aws](https://github.com/mitchellh/vagrant-aws)
* [vagrant-azure](https://github.com/MSOpenTech/Vagrant-Azure)
* [vagrant-bhyve](https://github.com/jesa7955/vagrant-bhyve)
* [vagrant-brightbox](https://github.com/NeilW/vagrant-brightbox)
* [vagrant-cloudstack](https://github.com/klarna/vagrant-cloudstack)
* [vagrant-cloudsigma](https://github.com/joaquinito01/vagrant-cloudsigma) (Requires CloudSigma account)
* [vagrant-digitalocean](https://github.com/smdahlen/vagrant-digitalocean)
* [vagrant-ganeti](https://github.com/osuosl/vagrant-plugin-ganeti)
* [vagrant-google](https://github.com/mitchellh/vagrant-google)
* [vagrant-hp](https://github.com/mohitsethi/vagrant-hp)
* [vagrant-joyent](https://github.com/someara/vagrant-joyent)
* [vagrant-kvm](https://github.com/adrahon/vagrant-kvm) (This project is [dead](https://github.com/adrahon/vagrant-kvm/blob/master/README.md), please use vagrant-libvirt instead.)
* [vagrant-linode](https://github.com/displague/vagrant-linode)
* [vagrant-libvirt](https://github.com/pradels/vagrant-libvirt)
* [vagrant-lxc](https://github.com/fgrehm/vagrant-lxc)
* [vagrant-lxd](https://gitlab.com/catalyst-it/devtools/vagrant-lxd)
* [vagrant-managed-servers](https://github.com/tknerr/vagrant-managed-servers)
* [vagrant-openstack](https://github.com/mat128/vagrant-openstack)
* [vagrant-openstack-provider](https://github.com/ggiamarchi/vagrant-openstack-provider)
* [vagrant-openvz](https://github.com/abrooke/vagrant-openvz)
* [vagrant-packet](https://github.com/jeefy/vagrant-packet)
* [vagrant-parallels](https://github.com/Parallels/vagrant-parallels)
* [vagrant-proxmox](https://github.com/telcat/vagrant-proxmox)
* [vagrant-rackspace](https://github.com/mitchellh/vagrant-rackspace)
* [vagrant-scaleway](https://github.com/kaorimatz/vagrant-scaleway)
* [vagrant-skytap](https://github.com/skytap/vagrant-skytap)
* [vagrant-softlayer](https://github.com/audiolize/vagrant-softlayer)
* [vagrant-vcenter](https://github.com/gosddc/vagrant-vcenter)
* [vagrant-vcloud](https://github.com/frapposelli/vagrant-vcloud)
* [vagrant-vcloudair](https://github.com/gosddc/vagrant-vcloudair)
* [vagrant-veertu](https://veertu.com/knowledgebase/vagrant-setup-instructions/)
* [vagrant-vmware-appcatalyst](https://github.com/vmware/vagrant-vmware-appcatalyst)
* [vagrant-vmware-esxi](https://github.com/josenk/vagrant-vmware-esxi)
* [vagrant-vsphere](https://github.com/nsidc/vagrant-vsphere)
* [vagrant-vultr](https://github.com/p0deje/vagrant-vultr)


## Provisioners

* [copy-my-conf](https://github.com/akshaymankar/copy_my_conf)
* [rubber](https://github.com/rubber/rubber/wiki/Running-with-vagrant)
* [vagrant-ansible-local](https://github.com/jaugustin/vagrant-ansible-local)
* [vagrant-baseline](https://github.com/bltavares/vagrant-baseline)
* [vagrant-fabric](https://github.com/wutali/vagrant-fabric)
* [vagrant-guest_ansible](https://github.com/vovimayhem/vagrant-guest_ansible)
* [vagrant-reload](https://github.com/aidanns/vagrant-reload) -- "Reload a VM as a provisioning step."
* [vagrant-saltdeps](https://github.com/joshughes/vagrant-saltdeps) -- Pull in all your salt formula dependencies and merge all your test grain and pillar data!
* [vagrant-sprinkle](https://github.com/jimmycuadra/vagrant-sprinkle)
* [vagrant-docker-compose](https://github.com/leighmcculloch/vagrant-docker-compose)
* [vagrant-cloudinit](https://github.com/jameskeane/vagrant-cloudinit)

## Shared Folders

* [vagrant-gatling-rsync](https://github.com/smerrill/vagrant-gatling-rsync) -- An rsync watcher for Vagrant 1.5.1+ that uses fewer host resources.
* [vagrant-nfs_guest](https://github.com/Learnosity/vagrant-nfs_guest) -- "reverse" NFS support, where the guest provides the nfs daemon and the host is the client
* [vagrant-winnfsd](https://github.com/GM-Alex/vagrant-winnfsd) -- NFS support for Windows hosts
* [vagrant-bindfs](https://github.com/gael-ian/vagrant-bindfs) -- Automate bindfs mount in guest (to work around NFS share permissions issues, for example)
* [vagrant-winrm-syncedfolders](https://github.com/Cimpress-MCP/vagrant-winrm-syncedfolders) -- WinRM folder synchronization using the native WinRM communicator.
* [vagrant-sshfs](https://github.com/dustymabe/vagrant-sshfs) -- mounts folders from the Vagrant host into the Vagrant guest via SSHFS.

## Host Interaction
* [vagrant-host-shell](https://github.com/phinze/vagrant-host-shell) -- simple vagrant provisioner that executes commands on the host
* [vagrant-notify](https://github.com/fgrehm/vagrant-notify) -- "redirects `notify-send` from guest to host machine"
* [vagrant-host-path](https://github.com/MOZGIII/vagrant-host-path) -- creates an environment variable with the path to the project's root dir on your host machine
* [vagrant-host-route](https://github.com/rtkwlf/vagrant-host-route) -- configures a network route on the host
* [vagrant-ip-show](https://github.com/rogeriopradoj/vagrant-ip-show) -- lists all IPs of VM networks
* [vagrant-guestip](https://github.com/mkuzmin/vagrant-guestip) -- displays IP address of a guest
* [vagrant-notify-forwarder](https://github.com/mhallin/vagrant-notify-forwarder) -- forwards file system events from the host to the guest
* [vagrant-triggers](https://github.com/emyl/vagrant-triggers) -- execute scripts on the host/guest before/after Vagrant commands

## Testing / BDD
* [vagrant-cucumber](https://github.com/scalefactory/vagrant-cucumber) -- allow cucumber to work with VMs
* [vagrant-serverspec](https://github.com/jvoorhis/vagrant-serverspec) -- run serverspec in your VM as provision step
* [vagrant_spec](https://github.com/miroswan/vagrant_spec) -- serverspec testing designed for distributed / clustered systems

## Networking
* [vagrant-auto_network](https://github.com/adrienthebo/vagrant-auto_network)
* [vagrant-software-bridge](https://github.com/rtkwlf/vagrant-software-bridge) -- configures a software bridge within a guest
* [vagrant-vlan](https://github.com/rtkwlf/vagrant-vlan) -- configures a VLAN within a guest
* [vagrant-vmware-dhcp](https://github.com/israelshirk/vagrant-vmware-dhcp) -- configures VMware's DHCP server to provide static IP addresses for guest VMs
* [vagrant-netinfo](https://github.com/vStone/vagrant-netinfo) -- displays forwarded ports for running machines.
* [vagrant-port-range](https://github.com/pr3sto/vagrant-port-range) -- mapping forwarded ports using range of host ports.

## Snapshots
* [sahara](https://github.com/jedi4ever/sahara) -- snapshot support (was integrated into Vagrant 1.8+ but has [issues](https://github.com/mitchellh/vagrant/issues/6752))


## Uncategorized
* [vagrant-remove-old-box-versions](https://github.com/swisnl/vagrant-remove-old-box-versions) -- Check your downloaded boxes and remove every box that is not the latest downloaded version.
* [vagrant-compose](https://github.com/fabriziopandini/vagrant-compose/) -- A Vagrant plugin that helps building complex multi-machine scenarios.
* [vagrant-registration](https://github.com/projectatomic/adb-vagrant-registration) -- registers your enterprise linux guests
* [vagrant-env](http://github.com/gosuri/vagrant-env) -- loads environment variables from .env into ENV
* [nugrant](https://github.com/maoueh/nugrant) -- "enhance Vagrantfile to allow user specific configuration values (from the `.vagrantuser` file)"
* [vagrant-apache2](https://github.com/Logaritmisk/vagrant-apache2)
* [vagrant-box-updater](https://github.com/spil-ruslan/vagrant-box-updater)
* [vagrant-box-version](https://github.com/eddsteel/vagrant-box-version)
* [vagrant-bundler](https://github.com/mocoso/vagrant-bundler)
* [vagrant-cachier](https://github.com/fgrehm/vagrant-cachier) -- caches packages for different managers like apt, yum
* [vagrant-camera](https://github.com/rainforestapp/vagrant-camera) -- "Takes screenshots of your Vagrant VMs"
* [vagrant-clean](https://github.com/mspaulding06/vagrant-clean) -- Destroys all Vagrant resources
* [vagrant-destroyer](https://github.com/canausa/vagrant-destroyer)
* [vagrant-disksize](https://github.com/sprotheroe/vagrant-disksize) -- Resize disk for VirtualBox machines
* [vagrant-exec](https://github.com/p0deje/vagrant-exec) -- an alternative to vagrant ssh + command execution
* [vagrant-ec2setup](https://github.com/yalab/vagrant-ec2setup)
* [vagrant-faster](http://github.com/rdsubhas/vagrant-faster) -- Make VMs run faster by allocating more Memory/CPUs based on machine capacity
* [vagrant-fog-box-storage](https://github.com/natlownes/vagrant-fog-box-storage) -- Gets the authenticated url of a private box to download cloud storage. (Not yet compatible with Vagrant 1.1)
* [vagrant-foodshow](https://github.com/express42/vagrant-foodshow) -- "Automatically create a NGROK tunnel to expose a local web server to the internet"
* [vagrant-git](https://github.com/Learnosity/vagrant-git) -- for checking out and updating git repos
* [vagrant-git-sync](https://github.com/jbornemann/vagrant-git-sync) -- For keeping Git-backed Vagrant configuration in-sync
* [vagrant-httpproxy](https://github.com/juliandunn/vagrant-httpproxy) -- for offline Chef cookbook development
* [vagrant-invade](https://github.com/frgmt/vagrant-invade) -- generates a Vagrantfile outside a YAML configuration
* [vagrant-junos](https://github.com/JNPRAutomate/vagrant-junos) -- control Junos-based guests, like Firefly Perimeter (vSRX)
* [vagrant-mongodb](https://github.com/smdahlen/vagrant-mongodb)
* [vagrant-mosh](https://github.com/p0deje/vagrant-mosh) -- connect to box using Mosh
* [vagrant-multi-putty](https://github.com/nickryand/vagrant-multi-putty) -- Allows you to use putty to ssh into VMs.
* [vagrant-mutate](https://github.com/sciurus/vagrant-mutate) -- Convert boxes to work with different providers
* [vagrant-node](https://github.com/fjsanpedro/vagrant-node) -- Allows you to control and manage a virtual environment remotely
* [vagrant-nodemaster](https://github.com/fjsanpedro/vagrant-nodemaster) -- Allows you to centrally control remote virtual environments.
* [vagrant-nuke](https://github.com/muteldar/vagrant-nuke) -- remove all boxes listed under vagrant box list
* [vagrant-orchestrate](https://github.com/Cimpress-MCP/vagrant-orchestrate) -- Cross platform deployment orchestration to existing servers using vagrant provisioners
* [vagrant-oscar](https://github.com/adrienthebo/oscar) -- "yaml based puppet multiple machine deployment"
* [vagrant-persistent-storage](https://github.com/kusnier/vagrant-persistent-storage) -- "creates a persistent storage and attaches it to guest machine"
* [vagrant-plugins](https://github.com/dotless-de/vagrant-plugins) -- Lists active vagrant plugins (with descriptions).
* [vagrant-pristine](https://github.com/fgrehm/vagrant-pristine) -- a single command for "vagrant destroy && vagrant up"
* [vagrant-proxy](https://github.com/clintoncwolfe/vagrant-proxy) -- Proxy HTTP requests made by the Vagrant provisioner.
* [vagrant-proxyconf](https://github.com/tmatilai/vagrant-proxyconf) -- Configures the VM to use proxies
* [vagrant-pushbullet](https://github.com/brettswift/vagrant-pushbullet) --  Get notified when provisioning completes on devices supported by [pushbullet.com](https://www.pushbullet.com/)
* [vagrant-pushover](https://github.com/tcnksm/vagrant-pushover) -- Add [pushover](https://pushover.net/) notification.
* [vagrant-rake](https://github.com/mitchellh/vagrant-rake) -- Allows rake tasks to be executed on the host using the vagrant rake command alleviating the need to SSH into the VM.
* [vagrant-rdp](https://github.com/tnayuki/vagrant-rdp) -- With this plugin, you can connect to windows VM by remote desktop connection.
* [vagrant-s3auth](https://github.com/WhoopInc/vagrant-s3auth) -- "Private, versioned Vagrant boxes hosted on Amazon S3."
* [vagrant-scp](https://github.com/invernizzi/vagrant-scp) -- SCP files to host VMs.
* [vagrant-screenshot](https://github.com/igorsobreira/vagrant-screenshot) -- Take screenshots of your VM. Useful to debug your VM while booting.
* [vagrant-shell-commander](https://github.com/fgimenez/vagrant-shell-commander) -- "Executes the given command on all the machines on multinode environments"
* [vagrant-sparseimage](https://github.com/Learnosity/vagrant-sparseimage) -- Creates and mounts a sparseimage for the guest system to share with the host (Only runs in OSX. Requires Vagrant v1.2+).
* [vagrant-sudo-rsync](https://github.com/typisttech/vagrant-sudo-rsync) -- Copy files from/to a Vagrant VM via `sudo rsync`
* [vagrant-timezone](https://github.com/tmatilai/vagrant-timezone) -- Configures the time zone of the guest (Only Linux and BSD supported. Requires Vagrant 1.2+).
* [vagrant-trellis-cert](https://github.com/TypistTech/vagrant-trellis-cert) -- Trust all Trellis self-signed certificates with single command.
* [vagrant-unify](https://github.com/edtoon/vagrant-unify) -- rsync files from guest->host, host->guest, or Unison files for bidirectional sync
* [vagrant-vbguest](https://github.com/dotless-de/vagrant-vbguest) -- automatically update VirtualBox guest additions if necessary
* [vagrant-vbinfo](https://github.com/miroswan/vbinfo) -- Vagrant plugin for outputting detailed VirtualBox information (Mac and Linux only)
* [vagrant-winrm-s](https://github.com/cimpress-mcp/vagrant-winrm-s) -- Extends the `:winrm` communicator with sspinegotiate-based domain authentication.


## Other (related)

* [vagrant-status](https://github.com/muteldar/vagrant-status) Powershell prompt modification that shows the basic status of Vagrant machines in the current directory
* [oh-my-vagrant](https://github.com/purpleidea/oh-my-vagrant) An easy to manipulate development environment for using vagrant with puppet, docker and more!
* [vagrant-web](https://github.com/catedrasaes-umu/vagrantweb). Web application (written in php) to manage machines (nodes), virtual machines, boxes, configurations, etc. It is required that each machine runs [vagrant-node](https://github.com/fjsanpedro/vagrant-node).

## Obsolete / Deprecated

* [bindler](https://github.com/fgrehm/bindler) -- "Bundler for vagrant" (no longer maintained)
* [docker-provider](https://github.com/fgrehm/docker-provider) (integrated into Vagrant 1.6+)
* [vagrant-boxen](https://github.com/fgrehm/vagrant-boxen) (deprecated)
* [vagrant-global-status](https://github.com/fgrehm/vagrant-global-status) (integrated into Vagrant 1.6+)
* [vagrant-plugin-bundler](https://github.com/tknerr/vagrant-plugin-bundler) (deprecated)
* [vagrant-windows](https://github.com/WinRB/vagrant-windows) (integrated into Vagrant 1.6+)
* [vagrant-salt](https://github.com/saltstack/salty-vagrant) (integrated into Vagrant 1.3+)
* [vagrant-snap](https://github.com/t9md/vagrant-snap) (does not work with Vagrant 1.0+)
* [vocker](https://github.com/fgrehm/vocker) (integrated into Vagrant 1.4+)
* [vagrant-rsync](https://github.com/cromulus/vagrant-rsync) -- "make it easy to rsync your shared folders" - for e.g. EC2 VMs
* [vagrant-mysql](https://github.com/Logaritmisk/vagrant-mysql) -- no longer maintained, purpose not clear
* [vagrant-vyatta](https://github.com/higebu/vagrant-vyatta) -- Vyatta guest support for Vagrant
* [vagrant-vbox-snapshot](https://github.com/dergachev/vagrant-vbox-snapshot) (integrated into Vagrant 1.8+)
* [vagrant-windows-hyperv](https://github.com/MSOpenTech/vagrant-windows-hyperv) -- Repository gone
* [vagrant-rekey-ssh](https://github.com/virtuald/vagrant-rekey-ssh) -- obsolete as of Vagrant 1.7+
* [vagrant-multiprovider-snap](https://github.com/scalefactory/vagrant-multiprovider-snap) -- snapshotting for Virtualbox, VMWare and HyperV through the same interface (integrated into Vagrant 1.8+) -- no longer actively maintained now that vagrant supports this natively.
* [ventriloquist](https://github.com/fgrehm/ventriloquist) -- no longer mantained
