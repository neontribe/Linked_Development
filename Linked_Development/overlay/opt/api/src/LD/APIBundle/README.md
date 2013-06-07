# Overview

The API server is designed to be a pluggable architecture.  The plug-ins are
defined in src/LD/APIBundle/Resources/config/services.yml in each case refer to
a php class.

For each defined route the sparql service is instantiated and a query builder
selected. The query is executed and the results passed to a response factory.
The response is then encoded to the correct data format.

## Queries

The queries for each route are defined in a yaml entry called sparqls.  The path
to a route maps to a set of array indexes.

e.g. /all/count/documents/theme will retrieve this array:

    queries:
      none:
          unlimited: true
          define: ~
          select: select count(distinct ?article) as ?count
          where: >
           where {
                  ?article a <http://purl.org/ontology/bibo/Article> .
                  OPTIONAL { ?article <http://purl.org/dc/terms/subject> ?theme .}
                  FILTER(!BOUND(?theme))
           }
      all:
          unlimited: true
          define: ~
          select: select distinct ?theme as ?url ?identifier ?themelabel as ?label count(distinct ?article) as ?count
          where: >
            where {
                   ?article a <http://purl.org/ontology/bibo/Article> .
                   ?art...

Each key is as follows:

 * `define`
       The first element of the query.  It will be placed first in the
       query string.  It is used to define name spaces.

 * `select`
       Used to define select/construct and describe section of the query

 * `where`
       The where clause.  See the Query builder section for details
       about optional filters.

 * `unlimited`
        If present then the limit/offset values are **NOT** added to the
        end of the query.

## Query Builders

Defined in src/LD/APIBundle/Services/QueryBuilders

Each instance should extend AbstractQueryBuilder which provides a number of
helper function.  It requires the definition of createQuery that will take the
elements of query and return a single string ready for submission to virtuoso.

The incoming route is matched against the `querybuilder` section in
services.yml.  That class will be instantiated and the query from above passed
into the createQuery.

LD\APIBundle\Services\QueryBuilders\DefaultQueryBuilder supplies a default
implementation that will suffice in most situations.  It will preprocess two
elements and then concatenate the sections into a single query string.

### Default query preprocessing

These function are defined in AbstractQueryBuilder and will be available to any
class that extends it.

#### addOffsetLImit

If the sparql config array (see above) does **not** have the value unlimited set
then the query will be limited using the offset and limit values specified in
the get parameters.  If the get parameters do not contain offset and limit
parameters then defaults will be loaded from the services.yml entries
`sparql_default_offset` and `sparql_default_limit`.

#### filterSubstitution

The filter substitution checks the where clause for filter definitions.  A filter
starts `++ FILTER` and ends `-- FILTER`.  Multiple filters are supported.  For
each of the defined filters they are processed to replace any query parameters
into the filters.  The get parameters are processed one at a time, for each
query parameter the filter is searched for `__PARAMETER_NAME__` and
replaced with the `__PARAMETER_VALUE__`.

## Factories

In an attempt to keep the the processing of data as flexible as possible the
results returned from virtuoso are passed into a factory to be parsed into a
data set.

Factories are specified in the same fashion as queries and sparqls in the
services.yml.  Each route defines a class that will be used as a processing
factory.

The factory interface defines the methods process and getResponse.  Raw EasyRDF
results from the virtuoso query is passed to the process function of the
factory.  The factory should then parse the response and store the results in
instance variable for that instantiation of the object.

The controller will call the getResponse function of the factory and will expect
a php array ready for serialisation.

## Response encoding

Response encoding is completely separated from factories and processing.  The
API controller provides a function `response`. This will auto detect the correct
format and encode the data into a Symfony response object.

See the APIController api docs for more details.

## Not yet implemented

### `Pagination`

Should not be difficult.