# Developer TASK

You are required to write a LoadBalancer class(es).

The class has 2 public methods.
The first – the constructor – takes two arguments. The first argument is a list of host instances that should be load balanced.
The second argument is the variant of load balancing algorithm to be used.

There are two variants: the first will simply pass the requests sequentially in rotation to each of the hosts in the list.
The second one will either take the first host that has a load under 0.75 or if all hosts in the list are above 0.75, it
will take the one with the lowest load.

The second method is called handleRequest(Request $request)and will load balance the request according to the variant passed on construction.

You can assume that the host instances class has the following API:

public float getLoad()
public void handleRequest(Request $request)

# Notes

1. `HostInstance` lists are assumed iterables, since it wasn't explicitly said they were arrays. Performance difference
should be negligible (mostly reiterating), if any - and all points below hold true.
2. PhpDocs are omitted in cases where they do not provide any additional value (like explicit function parameters)
3. Load balancer processes should run as permanent, long running processes (in contrast to running them under PHP-FPM or
apache-mod). This is suggested since running request-response cycles as separate processes would mean that server
would be quickly starved of resources: each new request would mean a new process which has to wait for the request
to be processes on the destination server. While those processes would not require much in terms of processing power,
they would still occupy some resources nonetheless.
It is advisable to use some sort of event-loop library, like `reactphp/event-loop` or `amp/event-loop` (`amphp/http-server`).
4. Considering that it should run in a permanent process, LoadBalancer class consisting of only constructor and
`handleRequest` method would probably be insufficient, unless the first argument be a mutable collection instance of
some sort - otherwise there would be no way to change `HostInstance` set while running. I believe it would be better to be
more explicit and allow this class to contain a setter method that would specifically set new `HostInstance` collection/array
(as a reaction to cpu loads changing).
Mutable instance would - in my opinion - be more obscure than explicit setting, while not providing any real performance
gains. Unless we really need a generator with some sort of state to keep track of called hosts.
5. `handleRequest` method should return some sort of Response instead of `void`. This would allow passing it back to
`Connection` instance in an event loop.
6. `HostInstance` instances should *NOT* be responsible for handling requests. Instead handling requests should be passed
to a separate class, allowing switching to a different implementation. A single `HostInstance` should be passed to the
handling method (likely `handleRequest`). This makes `HostInstance` practically a data object only.
7. Since host selection classes (strategies) have hosts passed as argument each time, we cannot assume those iterators
do not change or even are the same iterator instances as before. For this reason we cannot iterate on them and return
subsequent elements - instead I've used `array_rand` to select an element at random each time which should result in
similar behavior, i.e. equal distribution between host instances.
8. `UnderThresholdLoadStrategy` (second selection variant) is said to select first element that matches requirements,
which is trivial - but can result in load spikes when a lot of requests suddenly go to a server before cpu load is registered
on load balancing servers. Therefore it's almost mandatory to use at least a random selection instead, which means
that `UnderThresholdLoadStrategy` is actually a decorator around `RoundRobinStrategy` in this case (which is reflected
in solution).
I believe implementing that with "select first" should never be done, and it should be strongly advocated against it
even when it is explicit, like in this task. Possible performance issues are too severe.
9. CPU checks should be done in a separate process and passed into load balancing processes via database.
10. In cases where there are no available hosts a retry mechanism can be added.
11. Handling requests may contain a retry mechanism as well, but it should only do so for GET, HEAD and OPTIONS requests,
as those are commonly considered as "without side effects". This is outside of scope for LoadBalance selection required
in this task, but it would be important to remember.
12. For those long running processes there should exist a memory check and shutdown procedure. PHP memory limits would
simply kill the process, which would cause our load balancing to lose some requests. Disabling memory limits would
make potential memory leaks a lot harded to notice. Ideally shutdown procedure should allow in-memory requests to be handled
while no longer accepting new requests.
Running it as PHP-FPM instead would remedy this, but at the same time introduce multiple spawned processes for each request
(even for static content).
13. It makes more sense for `LoadBalancer::__construct` to have selection Strategy as first argument and some sort of
repository (with caching ideally) as second. This is due to the fact that `HostInstance` should be loaded from somewhere.
During initialization of services `HostInstance`s might not yet be needed, but would be initialized nonetheless if
this service is constructed. While Symfony does a good job to not initialize unnecessary services, I'd suggest injecting
a repository service instead.

# Final considerations

Unless there exists some sort of business requirement, implementing LoadBalancer on our own in PHP should not be
recommended. Considering the amount of battle tested, ready to use solutions (HAProxy, reverse-proxy in Nginx to name
a few) should be preferred. New implementation made from scratch would be less feature complete (e.g.: unable to work
with websockets, prone to crashes due to memory leaks, and less performant especially for static content).

# Running tests

A helper script is added to composer.json to run phpstan and phpunit:
`composer run test`
